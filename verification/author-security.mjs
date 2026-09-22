import fs from 'node:fs';import assert from 'node:assert/strict';import {execFileSync} from 'node:child_process';
const {chromium}=await import(process.env.PLAYWRIGHT_MODULE||'@playwright/test');const matrix=JSON.parse(fs.readFileSync('.verification/matrix.json'));const password=fs.readFileSync('.verification/password','utf8').trim();const out='.verification/results';let rows=[];
const b=await chromium.launch({args:['--no-sandbox']});
for(const site of matrix){const base='http://localhost:'+site.port;let r={name:site.name};let context=await b.newContext({viewport:{width:1440,height:1100}});let p=await context.newPage();
 try{
  const content='<!-- wp:tinyjpfont/huiji --><p class="wp-block-tinyjpfont-huiji">投稿者 <strong>太字</strong> <a href="https://example.com/">リンク</a><br>改行 ABC 123</p><!-- /wp:tinyjpfont/huiji --><!-- wp:paragraph --><p><tinyjpfontNoto class="wp-block-tinyjpfont-noto">旧書式</tinyjpfontNoto></p><!-- /wp:paragraph -->';
  const code='require "/var/www/html/wp-load.php";wp_set_current_user(1);echo wp_insert_post(array("post_title"=>"Author save roundtrip","post_status"=>"publish","post_author"=>get_user_by("login","audit-author")->ID,"post_content"=>base64_decode("'+Buffer.from(content).toString('base64')+'")));';
  const id=execFileSync('docker',['compose','-p','tinyjpfont-matrix','-f','.verification/compose.json','exec','-T',site.name,'php','-r',code],{encoding:'utf8'}).trim();r.post=id;
  await p.goto(base+'/wp-login.php');await p.locator('#user_login').fill('audit-author');await p.locator('#user_pass').fill(password);await p.locator('#wp-submit').click();await p.waitForURL(/wp-admin/);assert.equal((await context.request.get(base+'/wp-admin/admin.php?page=tinyjpfont')).status(),403);r.authorSettingsDenied=true;
  await p.goto(base+'/wp-admin/post.php?post='+id+'&action=edit');await p.waitForFunction(()=>window.wp?.data?.select('core/editor')?.getEditedPostContent()?.includes('投稿者'));
  const dismiss=p.getByRole('button',{name:/Disable tips|Dismiss tip/});if(await dismiss.count())await dismiss.first().click();const close=p.locator('.components-modal__frame').getByRole('button',{name:/Close/});if(await close.count())await close.first().click();
  const frame=p.frames().find(x=>x.url().startsWith('blob:'))||p.mainFrame();const text=frame.locator('.wp-block-tinyjpfont-huiji[contenteditable],.wp-block-tinyjpfont-huiji [contenteditable]').first();await text.click();await text.press('Control+End');await text.press('Shift+Enter');await text.pressSequentially('追記 日本語');
  await p.evaluate(async()=>await wp.data.dispatch('core/editor').savePost());await p.reload();await p.waitForFunction(()=>window.wp?.data?.select('core/editor')?.getEditedPostContent()?.includes('追記'));
  r.saved=await p.evaluate(()=>wp.data.select('core/editor').getEditedPostContent());for(const needle of ['<strong>太字</strong>','href="https://example.com/"','<br','wp-block-tinyjpfont-huiji','wp-block-tinyjpfont-noto','旧書式','追記'])assert.ok(r.saved.includes(needle),needle);r.markupPreserved=true;
  await p.screenshot({path:out+'/'+site.name+'-author.png'});
  const admin=await b.newContext({storageState:'.verification/'+site.name+'-auth.json'});const rejection=await admin.request.post(base+'/wp-admin/admin.php?page=tinyjpfont',{form:{tinyjpfont_select:'1',_wpnonce:'invalid-nonce'}});assert.equal(rejection.status(),403);r.nonceRejected=true;await admin.close();
  for(const endpoint of ['default-font-css.php','whole-font-css.php'])for(const query of ['fn[]=x','fn=%22%3B%7Dbody%7Bcolor:red%7D%2F*','fn=%3Cscript%3Ealert(1)%3C%2Fscript%3E']){let res=await context.request.get(base+'/wp-content/plugins/japanese-font-for-tinymce/'+endpoint+'?'+query);assert.equal(res.status(),200);assert.ok((res.headers()['content-type']||'').includes('text/css'));const css=await res.text();assert.ok(!css.includes('alert')&&!css.includes('color:red'));}
  r.cssInputValidation=true;r.result='PASS';
 }catch(e){r.result='FAIL';r.error=e.stack;await p.screenshot({path:out+'/'+site.name+'-author-failure.png'}).catch(()=>{})}
 rows.push(r);fs.writeFileSync(out+'/author-security.json',JSON.stringify(rows,null,2));console.log(site.name,r.result,r.error?.split('\n')[0]||'');await context.close();
}
await b.close();if(rows.some(r=>r.result==='FAIL'))process.exitCode=1;
