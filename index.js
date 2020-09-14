window.params = {
    user_id: 'none',
    limit: 10,
    offset: 0,
    type: true,
};

function changeUser(element) {
    params = {
        ...params,
        user_id: element.value,
        offset: 0,
    };
    updatePurchases();
    updateUser();
}

function changeType(element) {
    params = {
        ...params,
        type: element.checked == true ? 1 : 0,
    };
    updateUsers();
}

changeLimit = (element) => {
    const val = Number.parseInt(element.value);
    params = {
        ...params,
        limit: val != 'NaN' ? val : 10,
        offset: val * 1,
    };
    document.getElementById('nbPage').value = 1;
    updatePurchases();

};
changeOffset = (element) => {
    const val = Number.parseInt(element.value);
    params = {
        ...params,
        offset: val != 'NaN' ? val * params.limit : 10,
    };
    updatePurchases();
};

getPurchase = async () => {
    const check = ['', null, undefined];
    if (params.user_id && !check.find(value => value == params.user_id.trim())) {
        response = await fetch(`./index.php?user_id=${params.user_id}&limit=${params.limit}&offset=${params.offset}`);
        purchases = await response.json();
        return purchases;
    }
}

getUser = async () => {
    response = await fetch(`./index.php?type=${params.type}`);
    users = await response.json();
    return users
};

getUserInfo = async () => {
    response = await fetch(`./index.php?user_info=${params.user_id}`);
    user_info = await response.json();
    return user_info[0];

};

const updatePurchases = async () => {
    let purchases = await getPurchase();
    const tbody = document.getElementsByTagName('tbody')[0];

    tbody.innerHTML = '';

    if (purchases.length != 0) {

        purchases.forEach(purchase => {
            tr = document.createElement('tr');

            num_bon = document.createElement('td');
            num_bon.innerHTML = purchase.num_bon;

            nom = document.createElement('td');
            nom.innerHTML = purchase.nom;

            date = document.createElement('td');
            date.innerHTML = purchase.date;

            prix_ttc = document.createElement('td');
            prix_ttc.innerHTML = purchase.tot_tva;

            btn = document.createElement('td');
            btn.innerText = 'X';
            btn.onclick = function () {
                deletePurchase(purchase.num_bon);
            };

            tr.append(num_bon, nom, date, prix_ttc, btn);
            tbody.append(tr);
        });
    }

};


const updateUsers = async () => {
    let users = await getUser();
    if (users.length != 0) {
        lst = document.getElementById('users');
        lst.innerHTML = '';
        opt1 = document.createElement('option');
        opt1.innerText = 'Choisir un client';
        opt1.value = 'none';
        lst.append(opt1);

        users.forEach(user => {
            opt = document.createElement('option');
            opt.innerText = user.nom;
            opt.value = user.num_client;
            lst.append(opt);
        });
    }
};
updateUser = async () => {
    user_info = await getUserInfo();
    console.log(user_info);

    user = document.getElementById('user');
    user.style.display = 'block';

    nom = document.getElementById('nom');
    nom.innerText = user_info.nom;
    ville = document.getElementById('ville');
    ville.innerText = user_info.ville;
    civ = document.getElementById('civ');
    civ.innerText = user_info.civ;
    tel = document.getElementById('tel');
    tel.innerText = user_info.tel;


};

async function deletePurchase(num) {
    response = await fetch(`./index.php?num_bon=${num}`);
    respnum = await response.json();
    updatePurchases();
};

