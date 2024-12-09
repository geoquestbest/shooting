function swapTab(e) {
    console.log(e)
    let active = e.closest(".js-form-tab")

    active.classList.remove('active')
    active.nextElementSibling.classList.add('active')
}

let dropdown = document.querySelector('.cart-personalization__header')

if (dropdown) {
    document.querySelectorAll('.cart-personalization__header').forEach(dropdown=>{
        dropdown.addEventListener('click', (e)=>{
            e.currentTarget.parentElement.classList.toggle('hidden')
        })
    })
}

const productsList = document.querySelector('.cart-item__list')

const dropdownCards = document.querySelectorAll('.dropdown-card .dropdown-card__content')

if (dropdownCards.length) {
    dropdownCards.forEach((card, index) => {
        card.id = `productCard${index}`
    })
}

const addToCartButtons = document.querySelectorAll('.dropdown-card__button')

if (addToCartButtons.length) {
    addToCartButtons.forEach(btn => {
        btn.addEventListener('click', setCartProduct)
    })
}


const cardDetailsButtons = document.querySelectorAll('.dropdown-card__details')
const hideCardDetailsButtons = document.querySelectorAll('.dropdown-card__details-info > svg')

if (cardDetailsButtons.length) {
    cardDetailsButtons.forEach(btn => {
        btn.addEventListener('click', toggleCardDescription)
    })

    hideCardDetailsButtons.forEach(btn => {
        btn.addEventListener('click', toggleCardDescription)
    })    
}

function setCartProduct(e) {
    const button = e.currentTarget
    const cardContent = button.closest('.dropdown-card').querySelector('.dropdown-card__content')
    const productName = cardContent.querySelector('h3').innerHTML.trim()
    const productPrice = cardContent.querySelector('.dropdown-card__price').dataset.price
    const productVal = '€'

    if (!button.classList.contains('dropdown-card__button--active')) {
        const CART_PRODUCT_ITEM = `<li class="cart-item__list-item primary-text" data-btn="${cardContent.id}">
                                        <button class="list-item__delete primary-text" onClick="removeCartProduct(this, '${cardContent.id}')">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="14" viewBox="0 0 12.68 14.265">
                                                <path id="delete_24dp_5F6368_FILL0_wght400_GRAD0_opsz24" d="M162.378-825.735a1.526,1.526,0,0,1-1.119-.466,1.526,1.526,0,0,1-.466-1.119v-10.3H160v-1.585h3.963V-840h4.755v.792h3.963v1.585h-.793v10.3a1.526,1.526,0,0,1-.466,1.119,1.526,1.526,0,0,1-1.119.466Zm7.925-11.888h-7.925v10.3H170.3Zm-6.34,8.718h1.585v-7.133h-1.585Zm3.17,0h1.585v-7.133h-1.585Zm-4.755-8.718v0Z" transform="translate(-160 840)" fill="#5f6368"/>
                                            </svg>                                          
                                        </button>
                                        ${productName}
                                        <span class="list-item__price primary-text">${productPrice}${productVal}</span>
                                    </li>`

        productsList.insertAdjacentHTML(
            'beforeend',
            CART_PRODUCT_ITEM,
          );
    } else {
        document.querySelector(`[data-btn="${cardContent.id}"]`).remove()
    }

    button.classList.toggle('dropdown-card__button--active')
}

function removeCartProduct(item, cardId) {
    const parent = item.closest('.cart-item__list-item')

    parent.remove()
    document.getElementById(cardId).parentElement.querySelector('.dropdown-card__button--active').classList.remove('dropdown-card__button--active')
}

function toggleCardDescription(e) {
    const parent = e.currentTarget.closest('.dropdown-card')

    parent.classList.toggle('active')
}