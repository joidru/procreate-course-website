document.addEventListener('DOMContentLoaded', () => {
 const list=document.getElementById('evg-program-items'), template=document.getElementById('evg-program-template'), add=document.getElementById('evg-program-add');
 if(!list||!template||!add)return;
 const update=(item)=>{item.querySelector('.evg-program-item-label').textContent=item.querySelector('.evg-program-item-title').value||'Новая карточка';};
 const bind=(item)=>{
  item.querySelector('.evg-program-item-title').addEventListener('input',()=>update(item));
  item.querySelector('.evg-program-remove').addEventListener('click',()=>item.remove());
  item.querySelector('.evg-program-duplicate').addEventListener('click',()=>{const clone=item.cloneNode(true);item.after(clone);bind(clone);});
  item.querySelector('.evg-program-up').addEventListener('click',()=>{if(item.previousElementSibling)list.insertBefore(item,item.previousElementSibling);});
  item.querySelector('.evg-program-down').addEventListener('click',()=>{if(item.nextElementSibling)list.insertBefore(item.nextElementSibling,item);});
  item.querySelector('.evg-program-icon').addEventListener('change',(e)=>{const source=template.content.querySelector('.evg-program-icon-preview'); const options=document.querySelectorAll('.evg-program-icon'); const chosen=e.target.value; const existing=Array.from(list.querySelectorAll('.evg-program-item')).find(el=>el!==item&&el.querySelector('.evg-program-icon').value===chosen);if(existing){item.querySelector('.evg-program-icon-preview').innerHTML=existing.querySelector('.evg-program-icon-preview').innerHTML;}else{const samples=window.evgProgramIcons||{};if(samples[chosen])item.querySelector('.evg-program-icon-preview').innerHTML=samples[chosen];}});
  item.addEventListener('dragstart',e=>{e.dataTransfer.setData('text/plain','move');item.classList.add('is-dragging');});
  item.addEventListener('dragend',()=>item.classList.remove('is-dragging'));
  item.addEventListener('dragover',e=>e.preventDefault());
  item.addEventListener('drop',e=>{e.preventDefault();const dragged=list.querySelector('.is-dragging');if(dragged&&dragged!==item){const rect=item.getBoundingClientRect();list.insertBefore(dragged,e.clientY<rect.top+rect.height/2?item:item.nextSibling);}});
 };
 list.querySelectorAll('.evg-program-item').forEach(bind);
 add.addEventListener('click',()=>{const item=template.content.firstElementChild.cloneNode(true);list.appendChild(item);bind(item);item.querySelector('.evg-program-item-title').focus();});
});
