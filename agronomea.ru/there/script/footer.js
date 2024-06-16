// make function to find footer's first child span and add there inputed data like innerHTML = ludvor.ru
async function updateFooter(name, position){
  const yearNow = new Date().getFullYear();
  const footer = document.querySelector('footer');
  const spans = footer.querySelectorAll('span');
  const preLastSpan = spans[spans.length - 3];
  if (preLastSpan.innerHTML !== yearNow.toString()) {
    spans[spans.length - 2].innerHTML = `-${yearNow}`;
  }
  const span = footer.getElementsByTagName('span')[position];
  span.innerHTML = name;
}