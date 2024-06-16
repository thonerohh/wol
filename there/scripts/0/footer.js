const yearNow = new Date().getFullYear();
const footer = document.querySelector('footer');
const p = footer.querySelector('p');
const spans = p.querySelectorAll('span');
const preLastSpan = spans[spans.length - 3];

if (preLastSpan.innerHTML !== yearNow.toString()) {
  spans[spans.length - 2].innerHTML = `-${yearNow}`;
}
// make function to find footer's first child span and add there inputed data like innerHTML = ludvor.ru
async function updateFooter(name, position){
  const footer = document.querySelector('footer');
  const span = footer.getElementsByTagName('span')[position];
  span.innerHTML = name;
}