#!/usr/bin/env python3
"""Fetch pinned original font assets and generate the shared, reviewable manifest/CSS."""
import concurrent.futures, hashlib, json, pathlib, urllib.request
ROOT=pathlib.Path(__file__).resolve().parent.parent
OUT=ROOT/'.artifacts/fonts';OUT.mkdir(parents=True,exist_ok=True)
base='https://fonts.raspi0124.dev/v1'
repo='https://cdn.jsdelivr.net/gh/raspi0124/my-sites-files@'
fonts=[
 ('huifont','ふい字','Huifont',400,repo+'dc302549468ac811f2c12a171c10b90cd0551c3b/HuiFont109.woff2','HuiFont109.woff2','Huifont terms; prior notice confirmed by maintainer'),
 ('noto','Noto Sans Japanese','Noto Sans Japanese',300,'','NotoSansJP-DemiLight.woff','SIL OFL 1.1'),
 ('noto-thin','細字なNoto Sans Japanese','Noto Sans Japanese-100',100,'','NotoSansJP-Thin.woff','SIL OFL 1.1'),
 ('noto-black','太字なNoto Sans Japanese','Noto Sans Japanese-900',900,'','NotoSansJP-Black.woff','SIL OFL 1.1'),
 ('esenapaj','エセナパJ','esenapaj',400,repo+'097f6373c8d24abad960ab5ec25e001be4fe7bd0/esenapaj.ttf','esenapaj.ttf','SIL OFL 1.1'),
 ('honokamaru','ほのか丸ゴシック','honokamaru',400,repo+'80b7a9fadba7c729d00bcc81beba49deb97e19de/font_1_honokamarugo_1.1.ttf','honokamaru.ttf','Original author distribution terms'),
 ('kokorom','こころ明朝体','kokorom',400,repo+'0a9c3e242b46cd2d493db832a6eccba8f31b9da6/Kokoro.ttf','Kokoro.ttf','Original author distribution terms'),
 ('aoyanagit','青柳衡山フォントT','aoyanagiT',400,repo+'729d123c3a7de4ac2bb3a7cdbdedc707dde69120/aoyanagiT.ttf','aoyanagiT.ttf','Original author distribution terms'),
 ('tanukim','たぬき油性マジック','tanukiM',400,'','TanukiMagic.ttf','Original author distribution terms')]
def get_json(u):return json.load(urllib.request.urlopen(u,timeout=30))
pin=get_json('https://api.github.com/repos/ichiwa/google-web-font-jp-noto-sans-japanese/commits/master')['sha']
tanuki=get_json('https://api.github.com/repos/raspi0124/my-sites-files/commits?path=TanukiMagic.ttf&per_page=1')[0]['sha']
def fetch(row):
 id,label,family,weight,src,name,lic=row
 if not src:
  src=('https://raw.githubusercontent.com/ichiwa/google-web-font-jp-noto-sans-japanese/'+pin+'/fonts/'+name.replace('NotoSansJP-','')) if id.startswith('noto') else repo+tanuki+'/TanukiMagic.ttf'
 dest=OUT/name
 if not dest.exists():dest.write_bytes(urllib.request.urlopen(src,timeout=90).read())
 data=dest.read_bytes()
 if data[:4] not in [b'wOFF',b'wOF2',b'\x00\x01\x00\x00',b'OTTO']:raise ValueError('Invalid font: '+name)
 return dict(id=id,label=label,family=family,weight=weight,source=src,file=name,url=base+'/fonts/'+name,license=lic,sha256=hashlib.sha256(data).hexdigest(),bytes=len(data),format={'woff':'woff','woff2':'woff2','ttf':'truetype'}[dest.suffix[1:]])
manifest=list(concurrent.futures.ThreadPoolExecutor(max_workers=8).map(fetch,fonts))
(ROOT/'assets/fonts.json').write_text(json.dumps(manifest,ensure_ascii=False,indent=2)+'\n')
print(json.dumps([{'id':f['id'],'bytes':f['bytes']} for f in manifest]))
