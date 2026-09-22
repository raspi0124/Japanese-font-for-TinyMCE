#!/usr/bin/env python3
"""Mount the generated distribution ZIP, not the source checkout, in all test sites."""
from pathlib import Path
import json,zipfile,sys,subprocess,shutil
root=Path(__file__).resolve().parent.parent
archive=Path(sys.argv[1]).resolve();target=root/'.verification/packages'/archive.stem
with zipfile.ZipFile(archive) as z:
 assert z.testzip() is None
 for name in z.namelist():
  assert name.startswith('japanese-font-for-tinymce/') and '..' not in Path(name).parts
  assert not any(x in name for x in ['node_modules/','.verification/','.git/','api-token','.env'])
 z.extractall(target)
compose=root/'.verification/compose.json';data=json.loads(compose.read_text());source=root/'.verification/compose-source.json'
if not source.exists():shutil.copyfile(compose,source)
sites=[]
for name,service in data['services'].items():
 if name.startswith('db-'):continue
 sites.append(name);service['volumes']=[str(target/'japanese-font-for-tinymce')+':/var/www/html/wp-content/plugins/japanese-font-for-tinymce:ro' if ':/var/www/html/wp-content/plugins/japanese-font-for-tinymce:' in v else v for v in service['volumes']]
compose.write_text(json.dumps(data,indent=2))
subprocess.run(['docker','compose','-p','tinyjpfont-matrix','-f',str(compose),'up','-d','--no-deps']+sites,check=True)
print('Installed archive:',archive.name)
