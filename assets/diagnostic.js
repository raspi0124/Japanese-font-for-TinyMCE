(function () {
    var button=document.getElementById('tinyjpfont-diagnostic-run');
    if (!button) return;
    button.addEventListener('click',function () {
        var result=document.getElementById('tinyjpfont-diagnostic-result');
        var family=document.getElementById('tinyjpfont-diagnostic-font').value;
        result.style.fontFamily='';result.textContent=tinyjpfontDiagnostic.loading;button.disabled=true;
        if (!document.fonts) { result.textContent=tinyjpfontDiagnostic.error;button.disabled=false;return; }
        Promise.race([document.fonts.load('16px "'+family+'"','日本語'),new Promise(function (_,reject) {setTimeout(function(){reject(new Error('timeout'));},15000);})]).then(function (fonts) {
            if (!fonts.length) throw new Error('No font definition');
            result.style.fontFamily='"'+family+'",sans-serif';result.textContent=tinyjpfontDiagnostic.ok+' 日本語 ひらがな カタカナ 漢字 ABC 123';
        }).catch(function () {result.textContent=tinyjpfontDiagnostic.error;}).then(function () {button.disabled=false;});
    });
}());
