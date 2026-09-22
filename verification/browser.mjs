import fs from 'node:fs';import {execFileSync} from 'node:child_process';import path from 'node:path';import assert from 'node:assert/strict';
const engines=await import(process.env.PLAYWRIGHT_MODULE || '@playwright/test');
const root=path.resolve(import.meta.dirname,'..');const output=path.join(root,'.verification/results',process.env.BROWSER||'');fs.mkdirSync(output,{recursive:true});
const password=fs.readFileSync(path.join(root,'.verification/password'),'utf8').trim();
const matrix=JSON.parse(fs.readFileSync(path.join(root,'.verification/matrix.json')));
const fonts=JSON.parse(fs.readFileSync(path.join(root,'assets/fonts.json')));
const engine=process.env.BROWSER||'chromium';const browser=await engines[engine].launch({headless:true,args:engine==='chromium'?['--no-sandbox']:[]});const results=[];
for(const site of matrix.filter(x=>!process.env.SITE || x.name===process.env.SITE)){
 const context=await browser.newContext({javaScriptEnabled:true,viewport:{width:1440,height:1100}});const page=await context.newPage();let errors=[];page.on('pageerror',e=>errors.push(e.message));let r={...site};
 try{
  const base='http://localhost:'+site.port;
  const php='require "/var/www/html/wp-load.php";wp_set_current_user(1);echo wp_insert_post(array("post_title"=>"Legacy compatibility ZIP test","post_status"=>"publish","post_author"=>1,"post_content"=>base64_decode("'+Buffer.from(fs.readFileSync(path.join(root,'verification/legacy-content.html'),'utf8')).toString('base64')+'")));';
  const postId=execFileSync('docker',['compose','-p','tinyjpfont-matrix','-f',path.join(root,'.verification/compose.json'),'exec','-T',site.name,'php','-r',php],{encoding:'utf8'}).trim();r.postId=postId;

  await page.goto(base+'/wp-login.php',{waitUntil:'domcontentloaded'});await page.locator('#user_login').fill('audit-admin');await page.locator('#user_pass').fill(password);await page.locator('#wp-submit').click();await page.waitForURL(/wp-admin/);
  await context.storageState({path:path.join(root,'.verification',site.name+'-auth.json')});
  await page.goto(base+'/wp-admin/admin.php?page=tinyjpfont',{waitUntil:'domcontentloaded'});assert.equal(await page.locator('#tinyjpfont_gutenberg').isChecked(),true);await page.screenshot({path:path.join(output,site.name+'-settings.png')});
  await page.goto(base+'/wp-admin/post.php?post='+postId+'&action=edit',{waitUntil:'domcontentloaded'});await page.waitForFunction(()=>window.wp?.data?.select('core/block-editor')?.getBlocks()?.length || window.wp?.data?.select('core/editor')?.getBlocks()?.length,{timeout:30000});
  const modal=page.locator('.components-modal__frame');if(await modal.count()){const close=modal.getByRole('button',{name:/Close/});if(await close.count())await close.first().click();}
  const tips=page.getByRole('button',{name:/Disable tips|Dismiss tip/});if(await tips.count())await tips.first().click();
  r.registration=await page.evaluate(()=>({blocks:wp.blocks.getBlockTypes().filter(b=>b.name.startsWith('tinyjpfont')).map(b=>b.name),formats:wp.data.select('core/rich-text').getFormatTypes().filter(b=>b.name.startsWith('tinyjpfont')).map(b=>b.name),parsed:(wp.data.select('core/block-editor')||wp.data.select('core/editor')).getBlocks().map(b=>({name:b.name,valid:b.isValid}))}));
  assert.equal(r.registration.blocks.length,2);assert.ok(r.registration.parsed.every(b=>b.valid!==false));
  await page.evaluate(()=>{const store=wp.data.select('core/block-editor')||wp.data.select('core/editor');const dispatch=wp.data.dispatch('core/block-editor')||wp.data.dispatch('core/editor');dispatch.updateBlockAttributes(store.getBlocks()[0].clientId,{content:'日本語の文章。ひらがな カタカナ 漢字 ABC 123。'});});
  let frame=page.frames().find(f=>f.url().startsWith('blob:'))||page.mainFrame();const p=frame.locator('[data-type="core/paragraph"] [contenteditable="true"],p[data-type="core/paragraph"][contenteditable="true"]').first();await p.click();await p.press('Control+a');await page.getByRole('button',{name:'Bold',exact:true}).first().click();
  const more=page.getByRole('button',{name:'More',exact:true});const formatting=page.getByRole('button',{name:/More rich text controls/});if(await more.count())await more.first().click();else if(await formatting.count())await formatting.first().click();
  const fontButton=page.getByRole('button',{name:'Noto Sans Japanese',exact:true});if(await fontButton.count())await fontButton.last().click();else await page.getByText('Noto Sans Japanese',{exact:true}).filter({visible:true}).last().click();
  r.paragraph=await p.innerHTML();assert.ok(r.paragraph.includes('wp-block-tinyjpfont-noto'));assert.ok(r.paragraph.includes('<strong'));
  // Save with the editor API after actual toolbar interaction, then reload persisted data.
  await page.evaluate(async()=>{await wp.data.dispatch('core/editor').savePost();});await page.reload({waitUntil:'domcontentloaded'});await page.waitForFunction(()=>window.wp?.data?.select('core/editor')?.getEditedPostContent()?.includes('日本語')); 
  const close=page.locator('.components-modal__frame').getByRole('button',{name:/Close/});if(await close.count())await close.first().click();
  r.saved=await page.evaluate(()=>wp.data.select('core/editor').getEditedPostContent());assert.ok(r.saved.includes('wp-block-tinyjpfont-noto'));assert.ok(r.saved.includes('<strong'));
  const canvas=page.frames().find(f=>f.url().startsWith('blob:'))||page.mainFrame();await canvas.evaluate(async()=>{await document.fonts.load('24px "Noto Sans Japanese"','日本語');await document.fonts.load('24px "Huifont"','日本語');});
  await page.screenshot({path:path.join(output,site.name+'-editor.png')});
  await page.goto(base+'/?p='+postId,{waitUntil:'domcontentloaded'});
  r.fonts=await page.evaluate(async(fonts)=>{
   const list=document.createElement('section');list.id='font-specimens';document.body.append(list);let out=[];
   for(const f of fonts){let e=document.createElement('p');e.id='specimen-'+f.id;e.style.fontFamily='"'+f.family+'",sans-serif';e.style.fontSize='24px';e.textContent='日本語 ひらがな カタカナ 漢字 ABC 123';list.append(e);try{let loaded=await document.fonts.load('24px "'+f.family+'"','日本語');out.push({family:f.family,count:loaded.length,status:loaded.map(f=>f.status)})}catch(e){out.push({family:f.family,error:e.message})}}
   return out;
  },fonts);
  assert.ok(r.fonts.every(f=>f.count>0 && f.status.every(s=>s==='loaded')));
  if(engine==='chromium'){const cdp=await context.newCDPSession(page);await cdp.send('DOM.enable');await cdp.send('CSS.enable');let doc=await cdp.send('DOM.getDocument');r.renderedFonts=[];
  for(const f of fonts){let n=await cdp.send('DOM.querySelector',{nodeId:doc.root.nodeId,selector:'#specimen-'+f.id});let rendered=await cdp.send('CSS.getPlatformFontsForNode',{nodeId:n.nodeId});r.renderedFonts.push({id:f.id,fonts:rendered.fonts});assert.ok(rendered.fonts.some(x=>x.isCustomFont));}
  }
  await page.locator('#font-specimens').screenshot({path:path.join(output,site.name+'-fonts.png')});await page.screenshot({path:path.join(output,site.name+'-public.png')});
  r.errors=errors;assert.equal(errors.length,0);r.result='PASS';
 }catch(e){r.result='FAIL';r.failure=e.stack;r.errors=errors;await page.screenshot({path:path.join(output,site.name+'-failure.png')}).catch(()=>{});}
 results.push(r);console.log(site.name,r.result,r.failure?.split('\n')[0]||'');fs.writeFileSync(path.join(output,'browser.json'),JSON.stringify(results,null,2));await context.close();
}
await browser.close();if(results.some(x=>x.result==='FAIL'))process.exitCode=1;
