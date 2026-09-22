import fs from 'node:fs';
import {execFileSync} from 'node:child_process';
import assert from 'node:assert/strict';
const {chromium}=await import(process.env.PLAYWRIGHT_MODULE||'@playwright/test');
const browser=await chromium.launch({args:['--no-sandbox']});
const php=(site,code)=>execFileSync('docker',['compose','-p','tinyjpfont-matrix','-f','.verification/compose.json','exec','-T',site,'php','-r','$_SERVER["HTTP_HOST"]="localhost";require "/var/www/html/wp-load.php";'+code],{encoding:'utf8'}).trim();
const rows=[];fs.mkdirSync('.verification/results/dev5.00_5',{recursive:true});
try {
for(const site of JSON.parse(fs.readFileSync('.verification/matrix.json'))){
 const saved=JSON.parse(php(site.name,'echo json_encode(get_option("tinyjpfont_gutenberg",null));'));
 const p=await browser.newPage({viewport:{width:1440,height:1100},storageState:'.verification/'+site.name+'-auth.json'});
 const errors=[];p.on('pageerror',e=>errors.push(e.message));
 try {
 for(const value of [null,'0','1']){
 php(site.name,value===null?'delete_option("tinyjpfont_gutenberg");':'update_option("tinyjpfont_gutenberg","'+value+'");');
 await p.goto('http://localhost:'+site.port+'/wp-admin/admin.php?page=tinyjpfont');
 assert.equal(await p.locator('#tinyjpfont_gutenberg').isChecked(),value!=='0');
 assert.ok((await p.locator('.tinyjpfont-news').innerText()).includes('設定未保存の場合のみ'));
 assert.equal(await p.locator('.tinyjpfont-news').count(),1);
 const state=JSON.parse(php(site.name,'echo json_encode(array("stored"=>get_option("tinyjpfont_gutenberg",null),"editor"=>function_exists("tinyjpfont_gutenberg_editor_assets"),"presets"=>class_exists("WP_Theme_JSON_Data")?tinyjpfont_presets(new WP_Theme_JSON_Data(array("version"=>2)))->get_data():null));'));
 assert.equal(state.stored,value);assert.equal(state.editor,value!=='0');
 if(state.presets!==null)assert.equal(JSON.stringify(state.presets).includes('tinyjpfont-noto'),value!=='0');
 rows.push({site:site.name,value,editor:state.editor,result:'PASS'});
 if(value===null)await p.screenshot({path:'.verification/results/dev5.00_5/'+site.name+'-settings.png',fullPage:true});
 }
 // Turning off an initially enabled checkbox must survive a form save and reload.
 await p.locator('#tinyjpfont_gutenberg').uncheck();
 await Promise.all([p.waitForURL(/settings-updated=true/),p.locator('#submit').click()]);
 await p.reload();assert.equal(await p.locator('#tinyjpfont_gutenberg').isChecked(),false);
 assert.equal(php(site.name,'echo get_option("tinyjpfont_gutenberg");'),'0');
 assert.deepEqual(errors,[]);console.log(site.name,'PASS');
 } finally {php(site.name,saved===null?'delete_option("tinyjpfont_gutenberg");':'update_option("tinyjpfont_gutenberg","'+saved+'");');await p.close();}
}
} finally {await browser.close();fs.writeFileSync('.verification/results/dev5.00_5/defaults.json',JSON.stringify(rows,null,2));}
