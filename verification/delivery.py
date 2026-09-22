import concurrent.futures,json,pathlib,hashlib,subprocess,tempfile
root=pathlib.Path(__file__).resolve().parent.parent
fonts=json.loads((root/'assets/fonts.json').read_text())
def probe(f):
 with tempfile.TemporaryDirectory() as d:
  h=pathlib.Path(d)/'headers';b=pathlib.Path(d)/'body'
  subprocess.run(['curl','--fail','--silent','--show-error','--max-time','90','-H','Origin: https://wordpress-example.test','-D',str(h),'-o',str(b),f['url']],check=True)
  headers={k.lower():v.strip() for line in h.read_text().splitlines() if ':' in line for k,v in [line.split(':',1)]}
  sha=hashlib.sha256(b.read_bytes()).hexdigest();assert sha==f['sha256'];assert headers.get('access-control-allow-origin')=='*';assert 'immutable' in headers.get('cache-control','');assert headers.get('content-type','').startswith('font/')
  return dict(id=f['id'],sha256=sha,mime=headers['content-type'],cors=headers['access-control-allow-origin'],cache=headers['cache-control'],bytes=b.stat().st_size,result='PASS')
rows=list(concurrent.futures.ThreadPoolExecutor(max_workers=3).map(probe,fonts));(root/'.verification/results/delivery.json').write_text(json.dumps(rows,indent=2));print(json.dumps(rows,indent=2))
