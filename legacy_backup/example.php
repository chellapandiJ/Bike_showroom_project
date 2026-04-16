<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Bike Showroom</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
<style>
body{
    margin:0;
    font-family: Arial, sans-serif;
    scroll-behavior: smooth;
    background:#f1f3f6;
}

/* ===== SIDEBAR ===== */
#sidebarToggle{
    position:fixed;
    top:15px;
    left:15px;
    z-index:1100;
    cursor:pointer;
}
#sidebarToggle i{
    color:white;
}
#sidebar{
    width:0;
    position:fixed;
    top:0;
    left:0;
    height:100%;
    background:#1f3c88;
    overflow-x:hidden;
    transition:0.3s;
    padding-top:60px;
    z-index:1200;
}
#sidebar a{
    padding:15px 30px;
    display:block;
    color:#fff;
    font-weight:600;
    text-decoration:none;
    transition:0.3s;
}
#sidebar a:hover{
    background:#000080;
    text-decoration:none;
}

/* ===== TOP BAR ===== */
.topbar{
    background: linear-gradient(90deg,#1f3c88,#0000A0);
    padding:12px 40px;
    color:#fff;
    position:sticky;
    top:0;
    z-index:1000;
    display:flex;
    justify-content:space-between;
    align-items:center;
}
.logo{
    font-size:22px;
    font-weight:700;
    letter-spacing:1px;
}
.menu a{
    color:#fff;
    margin-left:20px;
    text-decoration:none;
    font-weight:500;
    cursor:pointer;
    transition:0.3s;
}
.menu a:hover{
    text-decoration:underline;
    color:#ffdd00;
}

/* ===== SLIDER ===== */
.slider-container{
    position:relative;
    width:100%;
}
.slider-img{
    width:100%;
    height:150px;
    object-fit:cover;
    display:block;
}

/* ===== SEARCH BAR OVERLAY ===== */
.search-overlay{
    position:absolute;
    top:55%;
    left:50%;
    transform:translate(-50%, -50%);
    width:350px;
    background:linear-gradient(to right,#fff,#f9f9f9);
    padding:10px 15px;
    border-radius:8px;
    box-shadow:0 6px 20px rgba(0,0,0,0.25);
}
.search-overlay input{
    border:none;
    outline:none;
    width:100%;
    font-size:16px;
}

/* ===== BIKE CARDS ===== */
.bike-card{
    border:none;
    transition:0.3s;
    cursor:pointer;
}
.bike-card:hover{
    transform:translateY(-6px);
    box-shadow:0 8px 25px rgba(0,0,0,0.3);
}
.bike-card img{
    width:100%;
    height:200px;
    object-fit:cover;
}
.price{
    color:#ff5722;
    font-size:18px;
    font-weight:bold;
}

/* ===== SECTIONS ===== */
.section{
    padding:60px 0;
}
.section h2{
    text-align:center;
    font-weight:700;
    margin-bottom:40px;
    color:#1f3c88;
}

/* About Flipkart Style */
.about-cards{
    display:flex;
    flex-wrap:wrap;
    justify-content:center;
    gap:30px;
}
.about-card{
    background:linear-gradient(135deg,#ffe082,#fff59d);
    width:280px;
    padding:25px 15px;
    border-radius:12px;
    box-shadow:0 4px 15px rgba(0,0,0,0.15);
    text-align:center;
    transition:0.4s;
}
.about-card:hover{
    transform:translateY(-8px);
    box-shadow:0 8px 25px rgba(0,0,0,0.25);
}
.about-card i{
    font-size:40px;
    color:#ff5722;
    margin-bottom:15px;
}
.about-card h5{
    font-weight:700;
    margin-bottom:10px;
    color:#333;
}
.about-card p{
    font-size:14px;
    color:#555;
}

/* Contact Flipkart Style */
.contact-cards{
    display:flex;
    flex-wrap:wrap;
    justify-content:center;
    gap:30px;
}
.contact-card{
    background:linear-gradient(135deg,#81d4fa,#b3e5fc);
    width:250px;
    padding:20px;
    border-radius:12px;
    box-shadow:0 4px 12px rgba(0,0,0,0.15);
    text-align:center;
    transition:0.4s;
}
.contact-card:hover{
    transform:translateY(-6px);
    box-shadow:0 6px 20px rgba(0,0,0,0.2);
}
.contact-card i{
    font-size:35px;
    color:#1f3c88;
    margin-bottom:12px;
}
.contact-card h6{
    font-weight:700;
    margin-bottom:8px;
    color:#1f3c88;
}
.contact-card p{
    font-size:14px;
    color:#333;
    margin:0;
}

/* ===== MODAL STYLES ===== */
.modal-header{
    background:#1f3c88;
    color:#fff;
}
.modal-title{
    font-weight:700;
}
.btn-close{
    filter: invert(1);
}

/* ===== BOOKING FORM ===== */
#bookingForm input, #bookingForm textarea{
    margin-bottom:10px;
}
</style>
</head>
<body>

<!-- SIDEBAR TOGGLE -->
<div id="sidebarToggle">
    <i class="fas fa-bars fa-2x"></i>
</div>

<!-- SIDEBAR -->
<div id="sidebar">
    <a href="admin.php">Admin Login</a>
    <a href="staff.php">Staff Login</a>
</div>

<!-- TOP BAR -->
<div class="topbar">
    <div class="logo">Bike Showroom</div>
    <div class="menu">
        <a href="#home">Home</a>
        <a href="#about">About</a>
        <a href="#contact">Contact</a>
    </div>
</div>

<!-- SLIDER -->
<div class="slider-container" id="home">
    <div id="slider" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="https://i.imgur.com/c.jpg" class="slider-img">
            </div>
        </div>
    </div>
    <div class="search-overlay">
        <input type="text" id="bikeSearch" placeholder="Search Your Dream Bike...">
    </div>
</div>

<!-- BIKE CARDS -->
<div class="container mt-4">
    <div class="row g-3" id="bikeContainer">
        <!-- Bikes will be rendered dynamically -->
    </div>
</div>

<!-- ABOUT SECTION -->
<div class="section" id="about">
<h2>About Our Showroom</h2>
<div class="about-cards">
<div class="about-card">
<i class="fas fa-motorcycle"></i>
<h5>Wide Range of Bikes</h5>
<p>Premium bikes including Royal Enfield, Yamaha, Honda, Bajaj & more.</p>
</div>
<div class="about-card">
<i class="fas fa-user-tie"></i>
<h5>Expert Guidance</h5>
<p>We help you choose the perfect bike based on your requirements.</p>
</div>
<div class="about-card">
<i class="fas fa-money-bill-wave"></i>
<h5>Easy Financing</h5>
<p>Flexible payment and finance options for hassle-free purchase.</p>
</div>
<div class="about-card">
<i class="fas fa-tools"></i>
<h5>After-Sales Service</h5>
<p>Professional maintenance and support after your purchase.</p>
</div>
</div>
</div>

<!-- CONTACT SECTION -->
<div class="section bg-light" id="contact">
<h2>Contact Us</h2>
<div class="contact-cards">
<div class="contact-card">
<i class="fas fa-map-marker-alt"></i>
<h6>Address</h6>
<p>123 Motor Street, Chennai, Tamil Nadu</p>
</div>
<div class="contact-card">
<i class="fas fa-phone"></i>
<h6>Phone</h6>
<p>+91 98765 43210</p>
</div>
<div class="contact-card">
<i class="fas fa-envelope"></i>
<h6>Email</h6>
<p>info@bikeshowroom.com</p>
</div>
<div class="contact-card">
<i class="fas fa-clock"></i>
<h6>Working Hours</h6>
<p>Mon-Sat, 9 AM - 7 PM</p>
</div>
</div>
</div>

<!-- BIKE DETAILS & BOOKING MODALS (same as your code) -->
<!-- ... include your existing modal HTML and booking logic ... -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// ===== SIDEBAR TOGGLE =====
const sidebar = document.getElementById('sidebar');
const sidebarToggle = document.getElementById('sidebarToggle');
sidebarToggle.addEventListener('click', ()=>{
    sidebar.style.width = (sidebar.style.width === '250px') ? '0' : '250px';
});

// ===== BIKE DATA & LOGIC =====
const bikes = [
    {id:1,name:"Royal Enfield",price:"₹ 1,95,000",img:"https://i.imgur.com/YQ9YQZp.png",desc:"Classic Royal Enfield motorcycle with premium features.",maxSlots:3,booked:0},
    {id:2,name:"Yamaha R15",price:"₹ 1,85,000",img:"https://i.imgur.com/TmQYb4B.png",desc:"Sporty Yamaha R15 with great handling and speed.",maxSlots:3,booked:0},
    {id:3,name:"Honda Shine",price:"₹ 85,000",img:"https://i.imgur.com/0sKpF7R.png",desc:"Reliable Honda Shine for daily commute.",maxSlots:3,booked:0},
    {id:4,name:"Bajaj Pulsar",price:"₹ 1,20,000",img:"https://i.imgur.com/d8zZJxS.png",desc:"Bajaj Pulsar, perfect mix of style and performance.",maxSlots:3,booked:0}
];

const bikeContainer = document.getElementById('bikeContainer');
function renderBikes(filter=""){
    bikeContainer.innerHTML = '';
    bikes.forEach(bike=>{
        if(bike.name.toLowerCase().includes(filter.toLowerCase())){
            let disabled = bike.booked >= bike.maxSlots ? 'disabled' : '';
            bikeContainer.innerHTML += `
            <div class="col-md-3 bike-item">
                <div class="card bike-card" data-id="${bike.id}">
                    <img src="${bike.img}">
                    <div class="card-body text-center">
                        <h6>${bike.name}</h6>
                        <p class="price">${bike.price}</p>
                        <div class="d-flex justify-content-between">
                            <button class="btn btn-outline-primary btn-sm viewDetailsBtn">View Details</button>
                            <button class="btn btn-success btn-sm bookBtn" ${disabled}>Book Now</button>
                        </div>
                        <small>${bike.maxSlots-bike.booked} slots left</small>
                    </div>
                </div>
            </div>
            `;
        }
    });
}
renderBikes();

const bikeSearch = document.getElementById('bikeSearch');
bikeSearch.addEventListener('input', ()=>{ renderBikes(bikeSearch.value); });

// ===== MODAL & BOOKING LOGIC SAME AS YOUR CODE =====
</script>
</body>
</html>
