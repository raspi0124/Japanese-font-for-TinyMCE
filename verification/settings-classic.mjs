import fs from 'node:fs';import assert from 'node:assert/strict';import {execFileSync} from 'node:child_process';
const {chromium}=await import(process.env.PLAYWRIGHT_MODULE||'@playwright/test');
const matrix=JSON.parse(fs.readFileSync('.verification/matrix.json'));const out='.verification/results';
function php(site,code){return execFileSync('docker',['compose','-p','tinyjpfont-matrix','-f','.verification/compose.json','exec','-T',site.name,'php','-r','require "/var/www/html/wp-load.php";'+code],{encoding:'utf8'}).trim()}
async function click(page,locator){await locator.evaluate(e=>e.scrollIntoView({block:'center',behavior:'instant'}));const box=await locator.boundingBox();assert.ok(box&&box.width&&box.height);await page.mouse.click(box.x+box.width/2,box.y+box.height/2)}
async function check(page,locator,value){if(await locator.isChecked()!==value)await click(page,locator);assert.equal(await locator.isChecked(),value)}
const b=await chromium.launch({args:['--no-sandbox']});const results=[];
for(const site of matrix.filter(x=>!process.env.SITE||x.name===process.env.SITE)){let r={name:site.name,checks:[]};const base='http://localhost:'+site.port;const ctx=await b.newContext({storageState:'.verification/'+site.name+'-auth.json',viewport:{width:1440,height:1100}});let p=await ctx.newPage();
 try{
  for(let i=0;i<(process.env.CLASSIC_ONLY?0:8);i++){
   const lite=(i&1)?'1':'0',cdn=(i&2)?'1':'0',footer=(i&4)?'1':'0',guten=((i&1)^((i>>1)&1))===1;
   if(i){await p.close();p=await ctx.newPage();}
   await p.goto(base+'/wp-admin/admin.php?page=tinyjpfont');await p.locator('#tinyjpfont_select').selectOption(lite);await check(p,p.locator('#tinyjpfont_check_cdn'),cdn==='1');await p.locator('#tinyjpfont_head').selectOption(footer);await check(p,p.locator('#tinyjpfont_gutenberg'),guten);await p.locator('#tinyjpfont_whole_font').selectOption(i===7?'Huifont':'noselect');await click(p,p.locator('#submit'));await p.waitForURL(/settings-updated/);
   await p.close();p=await ctx.newPage();await p.goto(base+'/?p=4');const style=p.locator('#tinyjpfont-styles-css');assert.equal(await style.count(),1);assert.equal((await style.getAttribute('href')).startsWith('https://fonts.raspi0124.dev/'),cdn==='1');assert.equal(await style.evaluate(e=>!!e.closest('head')),footer==='0');
   const loaded=await p.evaluate(async()=>{const f=await document.fonts.load('24px "Huifont"','日本語');return f.length>0&&f.every(x=>x.status==='loaded')});assert.ok(loaded);
   if(i===7){assert.ok((await p.locator('body').evaluate(e=>getComputedStyle(e).fontFamily)).includes('Huifont'));assert.ok((await p.locator('.wp-block-tinyjpfont-noto').last().evaluate(e=>getComputedStyle(e).fontFamily)).includes('Noto'));const snapshot=await ctx.newPage();await snapshot.goto(base+'/?p=4');await snapshot.screenshot({path:out+'/'+site.name+'-footer-lite.png'});await snapshot.close()}
   r.checks.push({lite,cdn,footer,guten,result:'PASS'});console.log(site.name,'settings',i,'PASS');
  }
  for(const advanced of [false,true]){
   php(site,'update_option("active_plugins",array("japanese-font-for-tinymce/japanese-tinymce.php","classic-editor/classic-editor.php"'+(advanced?',"tinymce-advanced/tinymce-advanced.php"':'')+'));update_option("classic-editor-replace","classic");update_option("tinyjpfont_whole_font","noselect");update_option("tinyjpfont_select","1");');
   await p.close();p=await ctx.newPage();await p.goto(base+'/wp-admin/post.php?post=4&action=edit');await click(p,p.locator('#content-tmce'));await p.waitForFunction(()=>window.tinymce?.get('content')?.initialized);const init=await p.evaluate(()=>({fonts:tinymce.get('content').settings.font_formats,css:tinymce.get('content').settings.content_css,toolbar:tinymce.get('content').settings.toolbar1}));assert.ok(init.fonts.includes('Arial=')&&init.fonts.includes('Huifont')&&init.fonts.includes('Noto Sans Japanese'));assert.ok(!init.fonts.includes('honokamaru'));assert.ok(String(init.css).includes('addfont_lite.css'));
   const editor=p.frameLocator('#content_ifr').locator('body');await editor.click();await editor.press('Control+End');await editor.press('Enter');await editor.pressSequentially('Classic 日本語 ABC 123');
   // Existing Advanced Editor Tools layout is left under that plugin's control.
   if(!advanced){const font=p.getByRole('button',{name:/Font Family|Font family|Fontfamily/});if(await font.count()){await font.first().click();await p.getByRole('menuitem',{name:'ふい字',exact:true}).click();await editor.pressSequentially(' ふい字');}}
   await p.locator('#content-html').click();assert.equal(await p.locator('#qt_content_tinyjpfont-noto').count(),1);await p.locator('#content').press('Control+End');await p.locator('#qt_content_tinyjpfont-noto').click();await p.locator('#content').pressSequentially(' Quicktags 日本語');await p.locator('#qt_content_tinyjpfont-noto').click();await Promise.all([p.waitForNavigation({waitUntil:'domcontentloaded'}),p.locator('#publish').click()]);await p.close();p=await ctx.newPage();await p.goto(base+'/wp-admin/post.php?post=4&action=edit');assert.ok((await p.locator('#content').inputValue()).includes('Quicktags 日本語'));await click(p,p.locator('#content-tmce'));await p.screenshot({path:out+'/'+site.name+'-classic'+(advanced?'-advanced':'')+'.png'});r.checks.push({classic:true,advanced,init,result:'PASS'});
  }
  r.result='PASS';
 }catch(e){r.result='FAIL';r.error=e.stack;await p.screenshot({path:out+'/'+site.name+'-settings-classic-failure.png'}).catch(()=>{})}
 finally{php(site,'update_option("active_plugins",array("japanese-font-for-tinymce/japanese-tinymce.php"));update_option("tinyjpfont_gutenberg","1");update_option("tinyjpfont_select","0");update_option("tinyjpfont_check_cdn","0");update_option("tinyjpfont_head","0");update_option("tinyjpfont_whole_font","noselect");');await ctx.close()}
 results.push(r);fs.writeFileSync(out+'/settings-classic'+(process.env.SITE?'-'+process.env.SITE:'')+'.json',JSON.stringify(results,null,2));console.log(site.name,r.result,r.error?.split('\n')[0]||'')
}
await b.close();if(results.some(x=>x.result==='FAIL'))process.exitCode=1;
