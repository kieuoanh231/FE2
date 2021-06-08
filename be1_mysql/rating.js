var rating = document.querySelector(".rating");
var ratingResult = document.querySelector(".ratingResult");
var ratingDisplayEle = document.querySelector(".rating-display");
var medium = 0;
var star = document.querySelectorAll(".hihi");

//add event listener
function clickStar(ele) {
    var clickedStar = ele;
    //value of the star
    var ratingValue = parseInt(clickedStar.getAttribute("value"));

    //change the color of the star
    for (var i = 0; i < ratingValue; i++) {
        rating.children[i].classList.add("clicked");
        for (var j = ratingValue; j <= 4; j++) {
            if (rating.children[j].classList.contains("clicked")) {
                rating.children[j].classList.remove("clicked");
            }
        }
    }
}

//function to calculate rating
async function calculateRating(ele) {
    //check how many elements are having clicked class
    var ratingCount = 0;
    var sum = 0;


    for (var i = 0; i < ele.children.length; i++) {
        if (ele.children[i].classList.contains("clicked")) {
            ratingCount++;
        }
    }

    const productId = document.querySelector('[name="id"]');
    const url = "../rating.php";
    const data = {
        rating: ratingCount,
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
    result.forEach(element => {
        sum += element.rating_number;
    });

    medium = sum / result.length;
    ratingDisplayEle.innerHTML = `product is selected ${medium} rating out of 5`;

    star.forEach(element => {
        var ratingValue = parseInt(element.getAttribute("value"));
        if (Math.round(medium) == ratingValue) {
            //change the color of the star
            for (var i = 0; i < ratingValue; i++) {
                ratingResult.children[i].classList.add("clicked");
                for (var j = ratingValue; j <= 4; j++) {
                    if (rating.children[j].classList.contains("clicked")) {
                        rating.children[j].classList.remove("clicked");
                    }
                }
            }
        }
    });
}