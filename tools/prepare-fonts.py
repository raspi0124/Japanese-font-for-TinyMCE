#!/usr/bin/env python3
"""Retrieve the immutable, hash-pinned assets listed in assets/fonts.json.
Original upstream URLs and conversion details are retained in the catalog.
This command never updates the catalog or silently substitutes new font bytes.
"""
from pathlib import Path
import concurrent.futures,hashlib,json,subprocess
ROOT=Path(__file__).resolve().parent.parent
OUT=ROOT/'.artifacts/fonts';OUT.mkdir(parents=True,exist_ok=True)
def fetch(font):
 target=OUT/font['file']
 if not target.exists():
  subprocess.run(['curl','--fail','--location','--silent','--show-error','--output',str(target),font['url']],check=True)
 if hashlib.sha256(target.read_bytes()).hexdigest()!=font['sha256']:
  raise RuntimeError('Font checksum mismatch: '+font['id'])
 return {'id':font['id'],'bytes':target.stat().st_size}
fonts=json.loads((ROOT/'assets/fonts.json').read_text())
print(json.dumps(list(concurrent.futures.ThreadPoolExecutor(max_workers=3).map(fetch,fonts))))
