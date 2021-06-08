 async function getProductById(productId) {
     const url = "productdetail.php";
     const data = {
         id: productId
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

     //nhận kết quả trả về
     const result = await request.json();

     const description = document.querySelector('#productDescription');
     const name = document.querySelector('#productName');
     name.innerHTML = result.product_name;
     description.innerHTML = result.product_description;
     var myModal = new bootstrap.Modal(document.getElementById('modal'), option);
     myModal.show();


 }

 //lấy product khi nhấn vào checkbox categories
 async function getProductByCategory() {
     var checkBox = document.querySelectorAll(".myCheck");
     const url = "productByCategory.php";
     var categoryId = [];

     checkBox.forEach(element => {
         if (element.checked) {
             categoryId.push(element.id);
         }
     });
     //  console.table(categoryId);
     const data = {
         id: categoryId
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

     //nhận kết quả trả về
     const result = await request.json();

     const productByCategory = document.getElementById("productByCategory");
     productByCategory.innerHTML = '';
     if (result === false) {
         return;
     }
     result.forEach(element => {
         productByCategory.innerHTML += `<div class="col-md-4">
          <div class="card">

              <a href="#">
                  <img src="./public/images/${element.product_photo} ?>" class="card-img-top" alt="...">
              </a>
              <div class="card-body">
                  <h5 class="card-title" data-bs-toggle="modal" data-bs-target="#modal" onclick="getProductById(${element.id})">${element.product_name}</h5>
                  <p class="card-text">${element.product_price}</p>
              </div>
          </div>
      </div>`
     });

 }

 //lấy tên sản phẩm khi nhập keyword trên input-search
 async function getProductByKeyWord() {
     var key = document.getElementById("fname").value.trim();
     const showRS = document.querySelector('#searchKey');
     showRS.innerHTML = '';
     showRS.style.display = 'none';

     if (key != '') {

         const url = "getdatasearch.php";
         const data = {
             key: key
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

         //Hiển thị kết quả
         result.forEach(element => {
             const productURI = encodeURIComponent(removeAccents(element.replace(/\s/g, '-')));
             const keyRegex = new RegExp(key, "gi");
             const productName = element.replace(keyRegex, `<b>${key}</b>`);
             showRS.style.display = 'block';
             showRS.innerHTML += `<a href="product.php/${productURI}" class="list-group-item list-group-item-action">${productName}</a>`
         });
     }

     console.log(showRS);

     function removeAccents(str) {
         return str.normalize('NFD')
             .replace(/[\u0300-\u036f]/g, '')
             .replace(/đ/g, 'd').replace(/Đ/g, 'D');
     }

 }

 //lấy product khi nhấn loadmore
 var page = document.querySelector(".load");
 page.addEventListener('click', function() {
     getProduct_Paginate();
 })

 async function getProduct_Paginate() {
     const listProduct = document.getElementById("productByCategory");
     const loadmore = document.querySelector('.load');
     const loader = document.querySelector('.loader');
     const url = "pagination.php";
     const data = {
         page: ++loadmore.value
     };

     loader.style.display = 'block';
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

     if (result.length < 3) {
         loadmore.setAttribute('disabled', '');

     }

     result.forEach(element => {
         listProduct.innerHTML += `<div class="col-md-4">
              <div class="card">
             <a href="#">
               <img src="./public/images/${element.product_photo} ?>" class="card-img-top" alt="...">
               </a>
              <div class="card-body">
               <h5 class="card-title" data-bs-toggle="modal" data-bs-target="#modal" onclick="getProductById(${element.id})">${element.product_name}</h5>
                   <p class="card-text">${element.product_price}</p>
                  </div>
            </div>
              </div>`
     });
     loader.style.display = 'none';
     loadmore.scrollIntoView();
 }