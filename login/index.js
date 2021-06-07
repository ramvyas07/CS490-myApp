const apiDiv = document.getElementById('cars');

axios
  .get('https://private-7c03b7-carsapi1.apiary-mock.com/manufacturers')
  .then((res) => {
    let cars = res.data;
    let carName = document.createElement('h3');
    let carImage = document.createElement('img');
    let carPrice = document.createElement('span');
    let carBrand = document.createElement('span');
    let carPower = document.createElement('span');

    cars.map((car) => {
      apiDiv.appendChild(carName);
    });
  });
