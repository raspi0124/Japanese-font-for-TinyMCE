#!/usr/bin/env python3
"""Create the five isolated, loopback-only WordPress installations."""
import pathlib,urllib.request,tarfile,zipfile,io,json,secrets,concurrent.futures
ROOT=pathlib.Path(__file__).resolve().parent.parent
OUT=ROOT/'.verification';OUT.mkdir(exist_ok=True)
matrix=[('wp51','5.1.25','tinyjpfont-php56',8891),('wp62','6.2.12','wordpress@sha256:7e46cf3373751b6d62b7a0fc3a7d6686f641a34a2a0eb18947da5375c55fd009',8892),('wp65','6.5.11','wordpress@sha256:deb7e5d058e277be30e7f7b93e8cf863b54130fcb2adf481a46280d76499d144',8893),('wp69','6.9.8','wordpress@sha256:29a3af5db27d8c1716367575280bbf449cd231ced707daf8ce411895c042ed76',8894),('wp71','7.1.1','wordpress@sha256:a85a30d9e7524d4577d6fdd09935b698687521026efbefd4b05d85ed6ae5dec7',8895)]
passfile=OUT/'password';password=passfile.read_text().strip() if passfile.exists() else secrets.token_urlsafe(20);passfile.write_text(password);passfile.chmod(0o600)
def setup(row):
 name,version,image,port=row;dest=OUT/name;dest.mkdir(exist_ok=True)
 if not (dest/'wordpress/wp-settings.php').exists():
  data=urllib.request.urlopen('https://wordpress.org/wordpress-'+version+'.tar.gz',timeout=120).read()
  with tarfile.open(fileobj=io.BytesIO(data)) as t:t.extractall(dest,filter='data')
 wp=dest/'wordpress'
 if not (wp/'wp-config.php').exists(): (wp/'wp-config.php').write_text("<?php\ndefine('DB_NAME','wordpress');define('DB_USER','wordpress');define('DB_PASSWORD','local-matrix-db');define('DB_HOST','db-"+name+"');define('DB_CHARSET','utf8mb4');define('DB_COLLATE','');$table_prefix='wp_';define('WP_DEBUG',true);define('WP_DEBUG_LOG',true);define('WP_DEBUG_DISPLAY',false);define('WP_AUTO_UPDATE_CORE',false);define('DISABLE_WP_CRON',true);define('FS_METHOD','direct');\n"+''.join("define('"+k+"','"+secrets.token_hex(32)+"');\n" for k in ['AUTH_KEY','SECURE_AUTH_KEY','LOGGED_IN_KEY','NONCE_KEY','AUTH_SALT','SECURE_AUTH_SALT','LOGGED_IN_SALT','NONCE_SALT'])+"if(!defined('ABSPATH'))define('ABSPATH',__DIR__.'/');require_once ABSPATH.'wp-settings.php';")
 for directory in [wp/'wp-content/uploads',wp/'wp-content/uploads/fonts']:
  directory.mkdir(exist_ok=True);directory.chmod(0o777)
 for slug,v in [('classic-editor','1.6.7'),('tinymce-advanced','5.2.1' if name=='wp51' else '5.9.2')]:
  target=wp/'wp-content/plugins'/slug
  if not target.exists():
   data=urllib.request.urlopen('https://downloads.wordpress.org/plugin/'+slug+'.'+v+'.zip',timeout=90).read();zipfile.ZipFile(io.BytesIO(data)).extractall(wp/'wp-content/plugins')
 return name
print(list(concurrent.futures.ThreadPoolExecutor(max_workers=5).map(setup,matrix)))
services={}
for name,version,image,port in matrix:
 services['db-'+name]={'image':'mariadb@sha256:79d59758afc91b89b120b0a8904d637f5a3b3e1c4900f29b740d6d46c72fef68','environment':{'MARIADB_ROOT_PASSWORD':'local-matrix-root','MARIADB_DATABASE':'wordpress','MARIADB_USER':'wordpress','MARIADB_PASSWORD':'local-matrix-db'},'volumes':[name+'-db:/var/lib/mysql'],'healthcheck':{'test':['CMD','healthcheck.sh','--connect','--innodb_initialized'],'interval':'5s','timeout':'5s','retries':36}}
 services[name]={'image':image,'entrypoint':['apache2-foreground'],'ports':['127.0.0.1:'+str(port)+':80'],'volumes':[str(OUT/name/'wordpress')+':/var/www/html',str(ROOT)+':/var/www/html/wp-content/plugins/japanese-font-for-tinymce:ro',str(ROOT/'verification/bootstrap.php')+':/tmp/bootstrap.php:ro',str(ROOT/'verification/uploads.ini')+':/usr/local/etc/php/conf.d/tinyjpfont-verification.ini:ro'],'environment':{'AUDIT_URL':'http://localhost:'+str(port),'AUDIT_PASSWORD':password},'depends_on':{'db-'+name:{'condition':'service_healthy'}}}
(OUT/'compose.json').write_text(json.dumps({'services':services,'volumes':{r[0]+'-db':{} for r in matrix}},indent=2))
(OUT/'matrix.json').write_text(json.dumps([dict(name=n,wordpress=v,image=i,port=p) for n,v,i,p in matrix],indent=2))
