
document.addEventListener("DOMContentLoaded",()=>{
 const menu=document.querySelector(".mobile"), links=document.querySelector(".links");
 if(menu&&links) menu.onclick=()=>links.classList.toggle("mobile-open");

 document.querySelectorAll(".fav").forEach(b=>{
   b.addEventListener("click",()=>{
     b.textContent=b.textContent==="♡"?"♥":"♡";
     b.style.color=b.textContent==="♥"?"#DCA52F":"";
   });
 });

 document.querySelectorAll("form.demo-form").forEach(f=>f.addEventListener("submit",e=>{
   e.preventDefault();
   const m=f.querySelector(".message");
   if(m)m.textContent="Your request has been submitted successfully.";
   else alert("Your request has been submitted successfully.");
 }));

 function applyFilters(form){
   const cards=[...document.querySelectorAll(".property-list .card")];
   if(!cards.length)return;
   const search=(form.querySelector("[name=search]")?.value||"").toLowerCase().trim();
   const type=(form.querySelector("[name=type]")?.value||"all").toLowerCase();
   const mode=(form.querySelector("[name=mode]")?.value||"all").toLowerCase();
   const budget=(form.querySelector("[name=budget]")?.value||"all").toLowerCase();
   const beds=(form.querySelector("[name=beds]")?.value||"all").toLowerCase();
   let visible=0;
   cards.forEach(card=>{
     const hay=(card.dataset.search||"").toLowerCase();
     const ctype=(card.dataset.type||"").toLowerCase();
     const cmode=(card.dataset.mode||"").toLowerCase();
     const cbudget=Number(card.dataset.price||0);
     const cbeds=Number(card.dataset.beds||0);
     let ok=true;
     if(search && !hay.includes(search))ok=false;
     if(type!=="all" && ctype!==type)ok=false;
     if(mode!=="all" && !cmode.includes(mode))ok=false;
     if(budget==="under50" && cbudget>=50)ok=false;
     if(budget==="50to100" && (cbudget<50||cbudget>100))ok=false;
     if(budget==="above100" && cbudget<=100)ok=false;
     if(beds!=="all" && cbeds<Number(beds))ok=false;
     card.classList.toggle("hidden",!ok); if(ok)visible++;
   });
   const no=document.querySelector(".no-results"); if(no)no.style.display=visible?"none":"block";
 }
 document.querySelectorAll("form.property-filter").forEach(f=>{
   f.addEventListener("submit",e=>{e.preventDefault();applyFilters(f)});
   f.querySelectorAll("input,select").forEach(el=>el.addEventListener("change",()=>applyFilters(f)));
   const s=f.querySelector("[name=search]"); if(s)s.addEventListener("input",()=>applyFilters(f));
 });
});
