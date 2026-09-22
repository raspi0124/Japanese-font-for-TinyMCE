import fs from 'node:fs';import assert from 'node:assert/strict';
const engines=await import(process.env.PLAYWRIGHT_MODULE||'@playwright/test');const out='.verification/results';const rows=[];
for(const name of ['chromium','firefox','webkit']){
 const b=await engines[name].launch({headless:true,args:name==='chromium'?['--no-sandbox']:[]});let r={browser:name};const ctx=await b.newContext({viewport:{width:1440,height:1100}});const p=await ctx.newPage();
 try{
  await p.goto('http://localhost:8895/?p=4');r.loaded=await p.evaluate(async()=>{const f=await document.fonts.load('24px "Noto Sans Japanese"','日本語');return f.length>0&&f.every(x=>x.status==='loaded')});assert.ok(r.loaded);await p.screenshot({path:out+'/'+name+'-public.png'});
  await ctx.route('https://fonts.raspi0124.dev/**',route=>route.abort());await p.reload();assert.ok((await p.locator('body').innerText()).includes('日本語'));r.outageReadable=await p.locator('.wp-block-tinyjpfont-noto').last().evaluate(e=>e.getBoundingClientRect().height>0);assert.ok(r.outageReadable);await p.screenshot({path:out+'/'+name+'-outage.png'});
  await ctx.unroute('https://fonts.raspi0124.dev/**');await p.reload();assert.ok(await p.evaluate(async()=>{try{return (await document.fonts.load('24px "Noto Sans Japanese"','日本語')).length>0}catch{return false}}));r.recovered=true;
  // CORS failure is simulated only in this browser, never by changing production security.
  r.corsRequests=[];await ctx.route('https://fonts.raspi0124.dev/**',async route=>{r.corsRequests.push(route.request().url());const res=await route.fetch();const headers=res.headers();headers['access-control-allow-origin']='https://wrong-origin.invalid';await route.fulfill({status:res.status(),body:await res.body(),headers})});await p.reload();r.corsRejected=await p.evaluate(async()=>{try{const f=new FontFace('CorsProbe','url(https://fonts.raspi0124.dev/v1/fonts/aoyanagiT.ttf?cors-probe='+Date.now()+')');document.fonts.add(f);await f.load();return false}catch{return true}});assert.ok(r.corsRejected);assert.ok((await p.locator('body').innerText()).includes('日本語'));await ctx.unroute('https://fonts.raspi0124.dev/**');
  r.result='PASS';
 }catch(e){r.result='FAIL';r.error=e.stack;await p.screenshot({path:out+'/'+name+'-rendering-failure.png'}).catch(()=>{})}
 await ctx.close();await b.close();rows.push(r);console.log(name,r.result,r.error?.split('\n')[0]||'');fs.writeFileSync(out+'/cross-browser.json',JSON.stringify(rows,null,2));
}
if(rows.some(r=>r.result==='FAIL'))process.exitCode=1;
