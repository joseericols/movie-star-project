<div class="col-md-12 review">
    <div class="row">
        <div class="col-md-1">
            <div class="profile-image-container review-image" style="background-image : url('<?= $BASE_URL ?>img/users/user.png')"></div>
        </div>
        <div class="col-md-9 author-details-container">
            <h4 class="author-name">
                <a href="#">Erico</a>
            </h4>
            <p><i class="fas fa-star"></i><?php $review->rating ?></p>
        </div>
        <div class="col-md-12">
            <p class="container-title">Comentário:</p>
            <p><?php $review->review ?></p>
        </div>
    </div>
</div>