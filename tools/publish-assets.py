#!/usr/bin/env python3
"""Upload immutable assets after provisioning. Refuses to replace different published bytes."""
import concurrent.futures,hashlib,json,os,pathlib,shutil,subprocess,urllib.request,urllib.error,urllib.parse
ROOT=pathlib.Path(__file__).resolve().parent.parent
wrangler=os.environ.get('WRANGLER_BIN') or shutil.which('wrangler')
if not wrangler:raise SystemExit('Set WRANGLER_BIN to the installed Wrangler executable')
env=os.environ.copy();tokenfile=pathlib.Path.home()/'.config/cloudflare/api-token'
if not env.get('CLOUDFLARE_API_TOKEN') and tokenfile.exists():env['CLOUDFLARE_API_TOKEN']=tokenfile.read_text().strip()
env['CLOUDFLARE_ACCOUNT_ID']='4de47e1e6d5c52a5283c707576ddea26'
manifest=json.loads((ROOT/'assets/fonts.json').read_text())
items=[(ROOT/'.artifacts/fonts'/f['file'],'v1/fonts/'+f['file'],'font/'+pathlib.Path(f['file']).suffix[1:]) for f in manifest]
items += [(ROOT/name,'v1/css/3/'+name,'text/css; charset=utf-8') for name in ['addfont.css','addfont_lite.css']]
items += [(ROOT/'LICENCE.txt','v1/LICENSE.txt','text/plain; charset=utf-8')]
items += [(p,'v1/licenses/'+p.name,'text/plain; charset=utf-8') for p in (ROOT/'licenses').iterdir() if p.is_file()]
def upload(item):
 path,key,mime=item;url='https://fonts.raspi0124.dev/'+urllib.parse.quote(key)
 probe=subprocess.run(['curl','--silent','--show-error','--max-time','30','--write-out','%{http_code}',url],stdout=subprocess.PIPE,check=True).stdout
 status=int(probe[-3:]);data=probe[:-3]
 if status==200:
  if hashlib.sha256(data).digest()!=hashlib.sha256(path.read_bytes()).digest():raise RuntimeError('Immutable object differs: '+key)
  return {'key':key,'status':'already-published'}
 if status!=404:raise RuntimeError('Unexpected public response '+str(status)+' for '+key)
 command=[wrangler,'r2','object','put','japanese-font-for-wordpress/'+key,'--file',str(path),'--content-type',mime,'--cache-control','public, max-age=31536000, immutable','--remote']
 p=subprocess.run(command,env=env,stdout=subprocess.PIPE,stderr=subprocess.STDOUT,text=True)
 if p.returncode:raise RuntimeError(key+': '+p.stdout)
 return {'key':key,'status':'uploaded'}
print(json.dumps(list(concurrent.futures.ThreadPoolExecutor(max_workers=3).map(upload,items)),indent=2))
