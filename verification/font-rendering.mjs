import fs from 'node:fs';import http from 'node:http';
const corsServer=http.createServer((req,res)=>{res.writeHead(200,{'Content-Type':'font/woff2','Access-Control-Allow-Origin':'https://wrong-origin.invalid'});res.end(fs.readFileSync('.artifacts/fonts/HuiFont109.woff2'))});await new Promise(resolve=>corsServer.listen(8898,'127.0.0.1',resolve));import assert from 'node:assert/strict';
const engines=await import(process.env.PLAYWRIGHT_MODULE||'@playwright/test');const out='.verification/results';const rows=[];
for(const name of ['chromium','firefox','webkit']){
 const b=await engines[name].launch({headless:true,args:name==='chromium'?['--no-sandbox']:[]});let r={browser:name};const ctx=await b.newContext({viewport:{width:1440,height:1100}});const p=await ctx.newPage();
 try{
  await p.goto('http://localhost:8895/?p=4');r.loaded=await p.evaluate(async()=>{const f=await document.fonts.load('24px "Noto Sans Japanese"','日本語');return f.length>0&&f.every(x=>x.status==='loaded')});assert.ok(r.loaded);await p.screenshot({path:out+'/'+name+'-public.png'});await p.reload();r.cachedReload=await p.evaluate(async()=>{await document.fonts.ready;return [...document.fonts].some(f=>f.family.replace(/["']/g,'')==='Noto Sans Japanese'&&f.status==='loaded')});assert.ok(r.cachedReload);
  await ctx.route('https://fonts.raspi0124.dev/**',route=>route.abort());await p.reload();assert.ok((await p.locator('body').innerText()).includes('日本語'));r.outageReadable=await p.locator('.wp-block-tinyjpfont-noto').last().evaluate(e=>e.getBoundingClientRect().height>0);assert.ok(r.outageReadable);await p.screenshot({path:out+'/'+name+'-outage.png'});
  await ctx.unroute('https://fonts.raspi0124.dev/**');await p.reload();assert.ok(await p.evaluate(async()=>{try{return (await document.fonts.load('24px "Noto Sans Japanese"','日本語')).length>0}catch{return false}}));r.recovered=true;
  // CORS failure is simulated only in this browser, never by changing production security.
  r.corsRejected=await p.evaluate(async()=>{try{const f=new FontFace('CorsProbe','url(http://127.0.0.1:8898/font.woff2?'+Date.now()+')');document.fonts.add(f);await f.load();return false}catch{return true}});if(name!=='webkit')assert.ok(r.corsRejected);r.corsBehavior=r.corsRejected?'blocked':'font CORS not enforced by this WebKit build (upstream bug 86817)';assert.ok((await p.locator('body').innerText()).includes('日本語'));
  r.result='PASS';
 }catch(e){r.result='FAIL';r.error=e.stack;await p.screenshot({path:out+'/'+name+'-rendering-failure.png'}).catch(()=>{})}
 await ctx.close();await b.close();rows.push(r);console.log(name,r.result,r.error?.split('\n')[0]||'');fs.writeFileSync(out+'/cross-browser.json',JSON.stringify(rows,null,2));
}
if(rows.some(r=>r.result==='FAIL'))process.exitCode=1;

await new Promise(resolve=>corsServer.close(resolve));
