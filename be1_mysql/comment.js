 //Create comment
 document.querySelector('.comment').addEventListener('click', createComment)

 async function createComment(id) {
     const commentContent = document.querySelector('#commentText');
     const productId = document.querySelector('[name="id"]');
     const commentResult = document.querySelector('.commentResult');
     commentResult.innerHTML = '';
     const url = "../comment.php";
     const data = {
         comment: commentContent.value,
         id: productId.value
     };

     //gửi yêu cầu lên server
     const request = await fetch(url, {
         method: 'POST',
         headers: {
             'Content-Type': 'application/json; charset=utf-8',
             'Accept': 'application/json; charset=utf-8'
         },
         body: JSON.stringify(data)
     });
     const result = await request.json();
     console.log(result);
     result.forEach(element => {
         commentResult.innerHTML += ` <div class="commentResult">${element.comment_content}</div>`
     });
     commentContent.value = '';

 }