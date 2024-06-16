// desktop fullscreen request
async function openFullscreen(){
  var elem = document.documentElement;

  if (elem.requestFullscreen) {
    elem.requestFullscreen();
  } else if (elem.webkitRequestFullscreen) { /* Safari */
    elem.webkitRequestFullscreen();
  } else if (elem.msRequestFullscreen) { /* IE11 */
    elem.msRequestFullscreen();
  }
}

// left and right buttons + make an icon **
async function scrollElement(){
  let randomKiss = 1;
  if (randomKiss == 1){
    if (action == 'right'){
      this.parentNode.parentNode.parentNode.parentNode.querySelector('.gallery').scrollLeft += 1000;
    } else if(action == 'left') {
      this.parentNode.parentNode.parentNode.parentNode.querySelector('.gallery').scrollLeft -= 1000;
    } else {}
  } else if (randomKiss == 2) {
    // rearrange images in gallery so clone and append, then remove first
    
  }
}

// hidden gallery and showing up characteristics *
async function showIn(){
  this.parentNode.parentNode.classList.toggle('formula');
  this.innerHTML = this.innerHTML === 'Подробнее' ? 'Скройся' : 'Подробнее';
}

// small pop up to make an order or call make icon of pin maybe. *
async function poppy(){
  let poppy = document.createElement('div');
  poppy.classList.add('poppy');

  console.log(poppy);
}
