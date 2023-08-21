window.addEventListener('load', (event) => {
    const LvWdgt = document.getElementById('livability-widget');
    const WdgtWdth = LvWdgt.offsetWidth;
     console.log(WdgtWdth);
     console.dir(LvWdgt);
     if (WdgtWdth > 768) {
         LvWdgt.style.height = "500px";
     } else {
         LvWdgt.style.height = "900px";
     }
 });
 window.addEventListener('resize', (event) => {
    const LvWdgt = document.getElementById('livability-widget');
    const WdgtWdth = LvWdgt.offsetWidth;
     console.log(WdgtWdth);
     console.dir(LvWdgt);
     if (WdgtWdth > 768) {
         LvWdgt.style.height = "500px";
     } else {
         LvWdgt.style.height = "900px";
     }

 });