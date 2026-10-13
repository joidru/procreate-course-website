document.addEventListener('DOMContentLoaded',()=>{
 const list=document.getElementById('evg-included-items'),template=document.getElementById('evg-included-template'),add=document.getElementById('evg-included-add');
 if(!list||!template||!add)return;
 function bind(item){
  const input=item.querySelector('.evg-included-item-text');
  input.addEventListener('input',()=>item.querySelector('.evg-included-item-label').textContent=input.value||'Новый пункт');
  item.querySelector('.evg-included-remove').addEventListener('click',()=>item.remove());
  item.querySelector('.evg-included-duplicate').addEventListener('click',()=>{const copy=item.cloneNode(true);item.after(copy);bind(copy);});
  item.querySelector('.evg-included-up').addEventListener('click',()=>{if(item.previousElementSibling)list.insertBefore(item,item.previousElementSibling);});
  item.querySelector('.evg-included-down').addEventListener('click',()=>{if(item.nextElementSibling)list.insertBefore(item.nextElementSibling,item);});
  item.addEventListener('dragstart',e=>{e.dataTransfer.setData('text/plain','move');item.classList.add('is-dragging');});
  item.addEventListener('dragend',()=>item.classList.remove('is-dragging'));
  item.addEventListener('dragover',e=>e.preventDefault());
  item.addEventListener('drop',e=>{e.preventDefault();const moving=list.querySelector('.is-dragging');if(moving&&moving!==item){const rect=item.getBoundingClientRect();list.insertBefore(moving,e.clientY<rect.top+rect.height/2?item:item.nextSibling);}});
 }
 list.querySelectorAll('.evg-included-item').forEach(bind);
 add.addEventListener('click',()=>{const item=template.content.firstElementChild.cloneNode(true);list.appendChild(item);bind(item);item.querySelector('input').focus();});
});