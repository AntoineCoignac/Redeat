document.addEventListener("DOMContentLoaded", (event) => {
    console.log("Bienvenue sur Redeat");

    let items = document.querySelectorAll(".items .item");
    let cardWrapper = document.querySelector(".card-wrapper");
    let card = cardWrapper.querySelector(".card");
    let leave = cardWrapper.querySelector(".leave");
    let cardCross = card.querySelector(".close");
    let img = card.querySelector("img");
    let title = card.querySelector(".title h2");
    let type = card.querySelector(".title p");
    let location = card.querySelector(".location a p");
    let monday = card.querySelector(".table #monday");
    let tuesday = card.querySelector(".table #tuesday");
    let wednesday = card.querySelector(".table #wednesday");
    let thursday = card.querySelector(".table #thursday");
    let friday = card.querySelector(".table #friday");
    let saturday = card.querySelector(".table #saturday");
    let sunday = card.querySelector(".table #sunday");
    let stock = card.querySelector(".stock .text");

    const days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];

    const today = new Date().getDay();
    const todayName = days[today];

    if (items && cardWrapper && card && cardCross && leave) {
        items.forEach(item => {
            item.addEventListener("click", function(e){
                let data =  this.getAttribute("data-shop");
                data = JSON.parse(
                    data.replace(/&quot;/g, '"')
                );
                console.log(data);
                img.src = `./image.php?user_id=${data.id}`;
                title.innerText = data.name;
                type.innerText = data.type;
                location.innerText = `${data["street-number"]} ${data["street-name"]}, ${data["zip-code"]} ${data["city"]}`;
                monday.innerText = `Lundi : ${data["monday-start"]} - ${data["monday-end"]}`;
                tuesday.innerText = `Mardi : ${data["tuesday-start"]} - ${data["tuesday-end"]}`;
                wednesday.innerText = `Mercredi : ${data["wednesday-start"]} - ${data["wednesday-end"]}`;
                thursday.innerText = `Jeudi : ${data["thursday-start"]} - ${data["thursday-end"]}`;
                friday.innerText = `Vendredi : ${data["friday-start"]} - ${data["friday-end"]}`;
                saturday.innerText = `Samedi : ${data["saturday-start"]} - ${data["saturday-end"]}`;
                sunday.innerText = `Dimanche : ${data["sunday-start"]} - ${data["sunday-end"]}`;
                let stockNumber = data[todayName+"-stock"];
                stock.innerHTML = `${stockNumber} panier${stockNumber > 0 ? "s" : ""} disponible${stockNumber > 0 ? "s" : ""}`;
                cardWrapper.classList.add("active");
            })
        })

        cardCross.addEventListener("click", function(e){cardWrapper.classList.remove("active")});
        leave.addEventListener("click", function(e){cardWrapper.classList.remove("active")});
    }

});