var total_discount = 0;

function swapTab(e) {
    console.log(e)
    const postcode = document.getElementById('postcode');
    if (postcode && postcode.value.length < 5) {
        postcode.classList.add('input-error');
        return false;
    }
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

const modalWrappers = document.querySelectorAll('.modal-wrapper')
const openModalButtons = document.querySelectorAll('[data-modal]')

openModalButtons.forEach((btn) => {
    btn.addEventListener('click', ()=>{
        const modal = document.getElementById(btn.dataset.modal)

        modal.classList.add('active')
        //clearInterval(timeoutModalAccept)
    })
})

modalWrappers.forEach((modal) => {
    modal.addEventListener('click', (e)=>{
        if (e.target.classList.contains('modal-wrapper')) {
            const acceptMessage = document.querySelector('.feedback-modal.accept')
            e.currentTarget.classList.remove('active')

            setTimeout(()=>{
                acceptMessage && acceptMessage.classList.remove('accept');
            }, 250)
        }
    })
})

const closeModalBtn = document.querySelector('.close-modal')
if (closeModalBtn) {
    closeModalBtn.addEventListener('click', (e)=>{
        e.preventDefault()
        modalWrappers[0].classList.remove('active')
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

/*const type = document.getElementById('type');
if (type) {
    setCookie('type', type.innerHTML);
    type.addEventListener('change', () => {
        setCookie('type', type.innerHTML);
    })
}*/

const date = document.getElementById('date');
if (date) {
    date.addEventListener('blur', () => {
        setCookie('date', date.value);
    })
    date.addEventListener('change', () => {
        setCookie('date', date.value);
    })
}

const postcode = document.getElementById('postcode');
if (postcode) {
    postcode.addEventListener('keyup', () => {
        setCookie('postcode', postcode.value);
    })
}

const city = document.getElementById('city');
if (city) {
    city.addEventListener('blur', () => {
        setCookie('city', city.value);
    })
}

const durations = document.querySelectorAll('[name="duration"]')
if (durations) {
    durations.forEach((e) => {
        e.addEventListener('click', () => {
            setCookie('duration', e.value);
            calc();
        })
    })
}

const numbers = document.querySelectorAll('[name="number"]')
if (numbers) {
    numbers.forEach((e) => {
        e.addEventListener('click', () => {
            setCookie('number', e.value);
            calc();
        })
    })
}

let productsCounter = 0

function setCartProduct(e) {
    const button = e.currentTarget
    const cardContent = button.closest('.dropdown-card').querySelector('.dropdown-card__content')
    const productName = cardContent.querySelector('h3').innerHTML.trim()
    const productType = cardContent.querySelector('.dropdown-card__price').dataset.type
    const productID = cardContent.querySelector('.dropdown-card__price').dataset.id
    const productPrice = cardContent.querySelector('.dropdown-card__price').dataset.price
    const conflicts = button.closest('.dropdown-card').dataset.conflicting
    const productVal = '€'
    const productModalItem = document.querySelector(`[data-id="modal-${productID}"]`)
    const cartRemove = document.querySelector('.cart-item__list [data-type="delivery"]')
    const cartRemove2 = document.querySelector('.cart-item__list [data-type="recovery"]')


    console.log(conflicts)
    if (conflicts) {
        let items = conflicts.split(',')
        console.log(items)
        items.forEach(el=>{
            let cartDel = document.querySelector(`.cart-item__list [data-type="options"][data-id="${el}"]`)
            console.log('Conflict id = ', el)
            console.log('Item in cart = ', cartDel)
            if (cartDel) {
                console.log('Init click', cartDel.firstElementChild)
                cartDel.firstElementChild.click()
            }
        })
    }

    console.log(cartRemove)
    if (button.closest('.dropdown-card').dataset.type == 'delivery' && cartRemove) {
        cartRemove.firstElementChild.click()
    }
    if (button.closest('.dropdown-card').dataset.type == 'delivery' && cartRemove2) {
        cartRemove2.firstElementChild.click()
    }
    if (button.closest('.dropdown-card').dataset.type == 'delivery') {
        let type = button.closest('.dropdown-card').querySelector('h3').innerHTML.includes('Livraison')
        console.log(type, button.closest('.dropdown-card').querySelector('h3').innerHTML)
        if (type) {
            document.querySelector('.dropdown__delivery').classList.add('active')
        } else {
            document.querySelector('.dropdown__delivery').classList.remove('active')
        }
        
    }


    

    if (!button.classList.contains('dropdown-card__button--active')) {
        const CART_PRODUCT_ITEM = `<li class="cart-item__list-item primary-text" data-btn="${cardContent.id}" data-type="${productType}" data-id="${productID}" data-price="${productPrice}">
                                        <button class="list-item__delete primary-text" onClick="removeCartProduct(this, '${cardContent.id}', '${productID}')">
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
        productsCounter++
    } else {
        document.querySelector(`[data-btn="${cardContent.id}"]`).remove()
        productsCounter--
    }

    button.classList.add('dropdown-card__button--active')
    if (button.closest('.modal-wrapper')) {
        document.querySelector(`.dropdown-card__price[data-id="${productID}"]`).closest('.dropdown-card').style.display = 'none';
    } else {        
        if (productModalItem) { productModalItem.style.display = 'none' }
    }
    setTimeout(()=>{
        button.closest('.dropdown-card').style.display = 'none';
        calc();
    },500)
}

function removeCartProduct(item, cardId, productID) {
    const parent = item.closest('.cart-item__list-item')
    const productModalItem = document.querySelector(`[data-id="modal-${productID}"]`)
    productsCounter--
    parent.remove()
    let btn = document.getElementById(cardId).parentElement.querySelector('.dropdown-card__button--active')
    let type = btn.closest('.dropdown-card').dataset.type == 'delivery'
    if (type == 'delivery') {
        document.querySelector('.dropdown__delivery').classList.remove('active')
    }
    btn.closest('.dropdown-card').style.display = 'flex'
    if (productModalItem) { productModalItem.style.display = 'flex' }
    setTimeout(()=>{
        btn.classList.remove('dropdown-card__button--active');
        calc();
    },100)
}

function changePopupButtonState() {
    let button = document.querySelector("#modal-extra .js-next")

    if (!button) { return }
    
    if (+productsCounter > 0) {
        button.innerHTML = 'CONTINUER'
        button.classList.add('active')
    } else {
        button.innerHTML = 'JE NE SUIS PAS INTÉRÉSSÉE'   
        button.classList.remove('active')  
    }
}

function toggleCardDescription(e) {
    const parent = e.currentTarget.closest('.dropdown-card')

    parent.classList.toggle('active')
}

function setCookie(name, value, expires, path, domain, secure) {
    document.cookie = name + '=' + escape(value) +
        ((expires) ? '; expires=' + expires : '') +
        ((path) ? '; path=' + path : '') +
        ((domain) ? '; domain=' + domain : '') +
        ((secure) ? '; secure' : '');
}

function calc() {
    const items = document.querySelectorAll('.cart-item__list-item');
    changePopupButtonState()
    var total = 0,
        bornes_types = [],
        delivery = [],
        options = [],
        recovery = [],
        add_price = 0,
        box_price = 0;
    const number = document.querySelector('[name="number"]:checked');
    var amount = number ? number.value : 1;
    const durations = document.querySelector('[name="duration"]:checked');
    var idx = durations.value > 1 ? (durations.value - 1) : 0;
    items.forEach((element) => {
        switch(element.dataset.type) {
            case 'bornes_types':
                bornes_types.push(element.dataset.id);
                var price_arr = element.dataset.price.split(',');
                box_price = price_arr[idx] * amount;
                document.querySelector('.box_price').innerHTML = amount + ' х ' + parseFloat(price_arr[idx]).toFixed(2) + '€';
                total = total + box_price;
            break;
            case 'delivery':
                delivery.push(element.dataset.id);
                total = total + +element.dataset.price;
            break;
            case 'options':
                options.push(element.dataset.id);
                total = total + +element.dataset.price;
            break;
            case 'recovery':
                recovery.push(element.dataset.id);
                total = total + +element.dataset.price;
            break;
        }
    });
    total = total - total_discount;


    if (durations) {
        add_price = durations.value > 1 ? (durations.value - 1) * box_price : 0;
    }


    document.querySelector('.total').innerHTML = (total + add_price).toFixed(2);
    setCookie('bornes_types', bornes_types.toString());
    setCookie('delivery', delivery.toString());
    setCookie('options', options.toString());
    setCookie('recovery', recovery.toString());
}

const selects = document.querySelectorAll('.select-wrapper')

if (selects.length > 0) {
    selects.forEach(select=>{
        let items = select.querySelectorAll('.select-item')

        select.addEventListener('click', (e)=>{
            e.currentTarget.classList.toggle('active')
        })

        items.forEach(el=>{
            el.addEventListener('click', (e)=>{
                let value = select.querySelector('.value')
                value.innerHTML = e.currentTarget.innerHTML
                setCookie('type', e.currentTarget.innerHTML);
            })
        })
    })
}