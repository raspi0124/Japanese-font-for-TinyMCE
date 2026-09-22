#!/usr/bin/env python3
"""Provision the dedicated public font bucket. Uses Wrangler's login; never logs credentials."""
import argparse, json, os, pathlib, tomllib, urllib.request, urllib.error
ACCOUNT = '4de47e1e6d5c52a5283c707576ddea26'
ZONE = '23a48bbb4086b202dfa3c472c5bac34c'
BUCKET = 'japanese-font-for-wordpress'
def request(path, method='GET', data=None):
    token = os.environ.get('CLOUDFLARE_API_TOKEN')
    if not token:
        config = pathlib.Path.home()/'.config/.wrangler/config/default.toml'
        token = tomllib.loads(config.read_text())['oauth_token']
    req = urllib.request.Request('https://api.cloudflare.com/client/v4/'+path, method=method,
        headers={'Authorization':'Bearer '+token,'Content-Type':'application/json'},
        data=json.dumps(data).encode() if data is not None else None)
    with urllib.request.urlopen(req, timeout=30) as response:
        result=json.load(response)
    if not result.get('success'): raise RuntimeError(str(result.get('errors')))
    return result.get('result')
def main():
    parser=argparse.ArgumentParser();parser.add_argument('--apply',action='store_true');args=parser.parse_args()
    root='accounts/'+ACCOUNT+'/r2/buckets'
    buckets=request(root)['buckets']
    domain=None
    for candidate in ['fonts.raspi0124.dev','tinyjpfont.raspi0124.dev']:
        records=request('zones/'+ZONE+'/dns_records?name='+candidate)
        if not records: domain=candidate;break
        if any(b['name']==BUCKET for b in buckets):
            existing=request(root+'/'+BUCKET+'/domains/custom').get('domains',[])
            if any(d['domain']==candidate for d in existing):domain=candidate;break
    if not domain:raise RuntimeError('Both domain candidates are already in use; no DNS records changed.')
    print(json.dumps({'bucket':BUCKET,'domain':domain,'apply':args.apply}))
    if not args.apply:return
    if not any(b['name']==BUCKET for b in buckets):request(root,'POST',{'name':BUCKET,'storageClass':'Standard'})
    request(root+'/'+BUCKET+'/cors','PUT',{'rules':[{'allowed':{'origins':['*'],'methods':['GET','HEAD']},'exposeHeaders':['ETag'],'maxAgeSeconds':86400}]})
    existing=request(root+'/'+BUCKET+'/domains/custom').get('domains',[])
    if not any(d['domain']==domain for d in existing):
        request(root+'/'+BUCKET+'/domains/custom','POST',{'domain':domain,'enabled':True,'zoneId':ZONE,'minTLS':'1.2'})
    print(json.dumps(request(root+'/'+BUCKET+'/domains/custom')))
if __name__=='__main__':main()
