#!/usr/bin/env python3
"""Build an installable ZIP from runtime files only."""
from pathlib import Path
import zipfile
root=Path(__file__).resolve().parent.parent
out=root/'.artifacts/japanese-font-wordpress.zip';out.parent.mkdir(exist_ok=True)
if not (root/'gutenjpfont/dist/blocks.js').is_file():raise SystemExit('Build editor assets first')
files=[]
for pattern in ['*.php','*.css','*.png','readme.txt','LICENCE.txt','includes/*.php','assets/*.json','assets/*.js','assets/*.css','gutenjpfont/dist/*','gutenjpfont/gutenjpfont.php','gutenjpfont/src/init.php']:
 files.extend(root.glob(pattern))
with zipfile.ZipFile(out,'w',zipfile.ZIP_DEFLATED) as z:
 for f in sorted(set(files)):
  if f.is_file() and not f.name.endswith('.map'):z.write(f,'japanese-font-for-tinymce/'+str(f.relative_to(root)))
print(out)
