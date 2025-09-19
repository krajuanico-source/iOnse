@extends('layouts/contentNavbarLayout')

@section('title', 'Dashboard Welfare')

@section('content')

   <!-- Toastr CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">




    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3">
        <i class="fas fa-award me-2"></i> Dashboard Welfare
    </h1>
    </div>

    <!-- Alert for Development Notice -->
    <div class="alert alert-warning">
        <strong>Notice:</strong> This page is currently under development. Some features may not be fully functional or available yet.
    </div>  


    <div class="header-section text-center">
        <div class="container">
            <h1 class="display-4 fw-bold">Fiscal Year Awardees</h1>
            <p class="lead">Celebrating Excellence and Outstanding Achievements</p>
    
        
        <div class="container mb-5">
            <h4 class="text-center mb-4">Featured Awardees</h4>
           <div id="awardeeCarousel" class="carousel slide position-relative" data-bs-ride="carousel">
                <div class="carousel-inner">
           
      <!-- Slide 1 -->
      <div class="carousel-item active">
        <div class="row justify-content-center">
          <div class="col-md-4 mb-3 d-none d-md-block">
            <div class="card text-center shadow-sm border border-2">
              <div class="card-body">
                <img src="https://media.craiyon.com/2025-04-13/0QSSgVvZTX-cN3EzZ2iegw.webp"
                     class="rounded-circle mb-3" style="width: 210px; height: 210px; object-fit: cover; border: 3px solid #dee2e6;"
                     alt="Awardee 2">
                <h5 class="fw-bold mb-1 text-dark">Elyna Cruz Bebe</h5>
                <p class="text-primary mb-1">Innovative Lang Me</p>
                <p class="text-muted small text-truncate text-wrap"
                    style="max-height: 40px; overflow: hidden; cursor: pointer;"
                    data-bs-toggle="collapse" data-bs-target="#awardeeDesc1" aria-expanded="false" aria-controls="awardeeDesc1">
                    Improved backend logic for enterprise systems.
                </p>
              </div>
            </div>
          </div>
          <div class="col-md-4 mb-3 d-none d-md-block">
            <div class="card text-center shadow-sm border border-2">
              <div class="card-body">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQSmXVdlTF8vsraebKArZtENR-B0ENZpWqjpw&s"
                     class="rounded-circle mb-3" style="width: 210px; height: 210px; object-fit: cover; border: 3px solid #dee2e6;"
                     alt="Awardee 2">
                <h5 class="fw-bold mb-1 text-dark">Samantha Cruz</h5>
                <p class="text-primary mb-1">Leadership Excellence</p>
                <p class="text-muted small">Led a department-wide restructuring initiative.</p>
              </div>
            </div>
          </div>

          <div class="col-md-4 mb-3 d-none d-md-block">
            <div class="card text-center shadow-sm border border-2">
              <div class="card-body">
                <img src="https://media.craiyon.com/2025-04-14/aBeNQ-bqRuC-YKZwS9ZHZQ.webp"
                     class="rounded-circle mb-3" style="width: 210px; height: 210px; object-fit: cover; border: 3px solid #dee2e6;"
                     alt="Awardee 3">
                <h5 class="fw-bold mb-1 text-dark">Daniel Mendoza</h5>
                <p class="text-primary mb-1">Service Excellence</p>
                <p class="text-muted small">Consistently delivers 5-star support.</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Slide 2 -->
      <div class="carousel-item">
        <div class="row justify-content-center">
          <div class="col-md-4 mb-3">
            <div class="card text-center shadow-sm border border-2">
              <div class="card-body">
                <img src="https://play-lh.googleusercontent.com/_qUtBpMVsGY-CLPx2DreAENHAbr4KHwBGn2w_3jhGSzoRVFRKn0SXUaK0wXSU0SJ7A"
                     class="rounded-circle mb-3" style="width: 210px; height: 210px; object-fit: cover; border: 3px solid #dee2e6;"
                     alt="Awardee 4">
                <h5 class="fw-bold mb-1 text-dark">Maria Santos</h5>
                <p class="text-primary mb-1">Team Player Award</p>
                <p class="text-muted small">Brings unity to multi-department teams.</p>
              </div>
            </div>
          </div>

          <div class="col-md-4 mb-3 d-none d-md-block">
            <div class="card text-center shadow-sm border border-2">
              <div class="card-body">
                <img src="https://w0.peakpx.com/wallpaper/920/405/HD-wallpaper-pretty-anime-girl-short-brown-hair-sweater-school-uniform-anime.jpg"
                     class="rounded-circle mb-3" style="width: 210px; height: 210px; object-fit: cover; border: 3px solid #dee2e6;"
                     alt="Awardee 5">
                <h5 class="fw-bold mb-1 text-dark">Jerome Tan</h5>
                <p class="text-primary mb-1">Customer Hero</p>
                <p class="text-muted small">Solved a major client issue in under 48 hours.</p>
              </div>
            </div>
          </div>

          <div class="col-md-4 mb-3 d-none d-md-block">
            <div class="card text-center shadow-sm border border-2">
              <div class="card-body">
                <img src="https://w0.peakpx.com/wallpaper/102/324/HD-wallpaper-anime-girl-anime-japan.jpg"
                     class="rounded-circle mb-3" style="width: 210px; height: 210px; object-fit: cover; border: 3px solid #dee2e6;"
                     alt="Awardee 6">
                <h5 class="fw-bold mb-1 text-dark">Nina de Vera</h5>
                <p class="text-primary mb-1">Innovation Support</p>
                <p class="text-muted small">Improved backend logic for enterprise systems.</p>
              </div>
            </div>
          </div>
        </div>
    </div>


    <!-- Slide 3 -->
      <div class="carousel-item">
        <div class="row justify-content-center">
          <div class="col-md-4 mb-3">
            <div class="card text-center shadow-sm border border-2">
              <div class="card-body">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSn7ZmSVCa1at_Aa9oFA2SANOB6EKP0bqvlhQ&s"
                     class="rounded-circle mb-3" style="width: 210px; height: 210px; object-fit: cover; border: 3px solid #dee2e6;"
                     alt="Awardee 4">
                <h5 class="fw-bold mb-1 text-dark">Yhena Villamil</h5>
                <p class="text-primary mb-1">QA Badass Award</p>
                <p class="text-muted small">Brings destroy the system.</p>
              </div>
            </div>
          </div>

          <div class="col-md-4 mb-3 d-none d-md-block">
            <div class="card text-center shadow-sm border border-2">
              <div class="card-body">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQSUmgJ4I8-jMmRii7wSpfM4lsicIr6fVw2ug&s"
                     class="rounded-circle mb-3" style="width: 210px; height: 210px; object-fit: cover; border: 3px solid #dee2e6;"
                     alt="Awardee 5">
                <h5 class="fw-bold mb-1 text-dark">Francis Jay Tan</h5>
                <p class="text-primary mb-1">Customer Hero of Isekai</p>
                <p class="text-muted small">Solved a major client issue in under 1 hours.</p>
              </div>
            </div>
          </div>

          <div class="col-md-4 mb-3 d-none d-md-block">
            <div class="card text-center shadow-sm border border-2">
              <div class="card-body">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSFQwTRqoPkpf_Kx7q76O3u4Hq0WZ3uLJ478g&s"
                     class="rounded-circle mb-3" style="width: 210px; height: 210px; object-fit: cover; border: 3px solid #dee2e6;"
                     alt="Awardee 6">
                <h5 class="fw-bold mb-1 text-dark">Kim Juanico de Vera</h5>
                <p class="text-primary mb-1">Innovation Support Specialist</p>
                <p class="text-muted small">Improved backend logic for whole yes.</p>
              </div>
            </div>
          </div>
        </div>
    </div>

    </div>

    <!-- Carousel Controls
  <button class="carousel-control-prev position-absolute top-50 start-0 translate-middle-y" type="button" data-bs-target="#awardeeCarousel" data-bs-slide="prev">
    <span class="carousel-control-prev-icon bg-dark rounded-circle p-3"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next position-absolute top-50 end-0 translate-middle-y" type="button" data-bs-target="#awardeeCarousel" data-bs-slide="next">
    <span class="carousel-control-next-icon bg-dark rounded-circle p-3"></span>
    <span class="visually-hidden">Next</span>
  </button>
  </div> -->
  
</div>


                

        </div>
    </div>


    




            <!-- Open Nominations Section -->
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="text-center mb-4">Open Nominations</h2>
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="card h-100 nomination-card">
                            <div class="card-body">
                                <h3 class="card-title">Innovation Champion Award</h3>
                                <div class="mb-3">
                                    <span class="badge bg-primary me-2"><i class="bi bi-calendar"></i> Deadline: June 15</span>
                                    <span class="badge bg-success"><i class="bi bi-award"></i> Annual Award</span>
                                </div>
                                <p class="card-text">Recognizing individuals who have developed innovative solutions or improvements that have significantly impacted our organization's performance, culture, or operational efficiency.</p>
                                <p class="text-muted"><small>All employees at any level are eligible. Self-nominations are accepted.</small></p>
                                <a href="#" class="btn btn-primary me-2">View Details</a>
                                <a href="#" class="btn btn-outline-primary">Nominate Someone</a>
                            </div>
                        </div>
                    </div>




            <div class="col-md-6 mb-4">
                        <div class="card h-100 nomination-card">
                            <div class="card-body">
                                <h3 class="card-title">Leadership Excellence</h3>
                                <div class="mb-3">
                                    <span class="badge bg-primary me-2"><i class="bi bi-calendar"></i> Deadline: July 1</span>
                                    <span class="badge bg-success"><i class="bi bi-award"></i> Bi-Annual Award</span>
                                </div>
                                <p class="card-text">Honoring leaders who have demonstrated exceptional leadership qualities, inspired their teams, and delivered outstanding results while maintaining high ethical standards.</p>
                                <p class="text-muted"><small>Open to managers and above with at least 1 year in leadership role.</small></p>
                                <a href="#" class="btn btn-primary me-2">View Details</a>
                                <a href="#" class="btn btn-outline-primary">Nominate Someone</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>              



                <!-- View More Section -->
        <div class="row">
            <div class="col-12 text-center">
                <div class="card py-4 bg-white shadow-sm">
                    <!-- Recent Awards Section -->
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="text-center mb-4">Recent Awards</h2>    
                    <h3>View All Awards & Nominations</h3>
                    <p class="mb-4">Discover all available awards, eligibility criteria, and nomination processes</p>
                    <a href="#" class="btn btn-lg btn-primary px-4">
                        <i class="bi bi-arrow-right-circle">Explore</i>
                    </a>
                </div>
            </div>
        </div>
    </div>









    

@endsection