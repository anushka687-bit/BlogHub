<footer class="bh-footer">
    <div class="container">
        <div class="row gy-4">
            <div class="col-lg-4">
                <h5><i class="bi bi-pencil-square me-2"></i>BlogHub</h5>
                <p class="text-secondary">A community blogging platform where writers share ideas, stories, and expertise with the world.</p>
                <div class="mt-3">
                    <a href="#" class="social-icon"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="social-icon"><i class="bi bi-twitter-x"></i></a>
                    <a href="#" class="social-icon"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="social-icon"><i class="bi bi-linkedin"></i></a>
                </div>
            </div>

            <div class="col-lg-2 col-md-3">
                <h5>Company</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="#">About</a></li>
                    <li class="mb-2"><a href="#">Contact</a></li>
                    <li class="mb-2"><a href="#">Privacy Policy</a></li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-3">
                <h5>Explore</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="{{ route('blogs.index') }}">Browse Blogs</a></li>
                    <li class="mb-2"><a href="{{ route('register') }}">Become an Author</a></li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-3">
                <h5>Newsletter</h5>
                <p class="text-secondary small">Get the latest posts delivered right to your inbox.</p>
                <div class="input-group">
                    <input type="email" class="form-control bh-form-control" placeholder="Your email">
                    <button class="btn btn-gradient ms-2">Join</button>
                </div>
            </div>
        </div>

        <div class="bh-divider"></div>

        <p class="text-center text-secondary small mb-0">&copy; {{ date('Y') }} BlogHub. All rights reserved.</p>
    </div>
</footer>