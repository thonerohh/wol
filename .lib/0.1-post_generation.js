// generatePostsFurther with timeout 1200
// setTimeout(generatePostsFurther, 1200);
// while(current + amount < images.length){
//   if (current + (amount * 2) > images.length && amount !== 1){
//     amount = amount / 2;
//     // amount round
//     amount = Math.round(amount);
//   } else {
//     await generatePosts(images, amount);
//   }
// }

// if (current + (amount * 2) > images.length && amount !== 1){
//   amount = amount / 2;
//   // amount round to smaller
//   amount = Math.floor(amount);
// }

// for(let i = current; i < images.length; i += amount){
//   console.log(i);
//   if (current + amount * 2 > images.length && amount !== 1){
//     amount = amount / 2;
//     amount = Math.floor(amount);
//     i = current;
//   } else {
//     await generatePosts(images, amount);
//   }
// }