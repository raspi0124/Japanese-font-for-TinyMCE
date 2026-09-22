import fs from 'node:fs';
import assert from 'node:assert/strict';
import {execFileSync} from 'node:child_process';
const {chromium}=await import(process.env.PLAYWRIGHT_MODULE);
const browser=await chromium.launch({args:['--no-sandbox']});
const out='.verification/results/dev5.00_7';fs.mkdirSync(out,{recursive:true});
const rows=[];
try { for(const site of JSON.parse(fs.readFileSync('.verification/matrix.json'))){
 const p=await browser.newPage({viewport:{width:1440,height:1100},storageState:'.verification/'+site.name+'-auth.json'});
 const errors=[];p.on('pageerror',e=>errors.push(e.message));
 await p.goto('http://localhost:'+site.port+'/wp-admin/admin.php?page=tinyjpfont');
 const news=p.locator('.tinyjpfont-news');assert.equal(await news.count(),1);
 const text=await news.innerText();assert.ok(text.includes('4.30から'));assert.ok(text.includes('Font Library'));assert.ok(text.includes('既存記事と保存済み設定'));
 const response=await p.request.get(await news.locator('a').getAttribute('href'));assert.equal(response.status(),200);assert.ok((await response.text()).includes('= 5.00-dev.7 ='));
 await p.screenshot({path:out+'/'+site.name+'-settings.png',fullPage:true});assert.deepEqual(errors,[]);
 const lint=execFileSync('docker',['compose','-p','tinyjpfont-matrix','-f','.verification/compose.json','exec','-T',site.name,'php','-l','/var/www/html/wp-content/plugins/japanese-font-for-tinymce/notice.php'],{encoding:'utf8'});assert.ok(lint.includes('No syntax errors'));
 rows.push({site:site.name,notice:true,changelogLink:200,pageErrors:errors,phpSyntax:'PASS'});await p.close();console.log(site.name,'PASS');
}}finally{await browser.close();fs.writeFileSync(out+'/news.json',JSON.stringify(rows,null,2));}
