<!-- modal review starts -->

<div class="modal_review" id="modalReview">
    <div class="modal_review_content">
        <span class="close" id="closeReview">&#x2716;</span>
        <form method="post" action="">
            <div class="box">
                <p>your name <span>*</span></p>
                <input type="text" name="name" id="name" placeholder="your name" required class="input">
            </div>
            <div class="box">
                <p>your review <span>*</span></p>
                <textarea name="review" id="review" placeholder="write your review" required class="input"></textarea>
            </div>

            <input type="submit" name="submit_review" value="submit" class="btn">
        </form>
    </div>
</div>

<!-- modal review ends -->

<style>
    .modal_review_content {
        background-color: var(--main-color);
        padding: 2rem;
        border-radius: 5px;
        height: 50rem;
        width: 80rem;
    }

    .modal_review {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.7);
        justify-content: center;
        align-items: center;
        z-index: 3;
    }

    .modal_review .close {
        font-size: 2rem;
        color: var(--sub-color);
        cursor: pointer;
        position: fixed;
        top: 10px;
        right: 10px;
    }

    .modal_review .modal_review_content form .box p {
        font-size: 2rem;
        color: var(--sub-color);
    }

    .modal_review .modal_review_content form .box {
        flex: 1 1 40rem;
        margin: 1rem 0;
    }

    .modal_review .modal_review_content form .box .input {
        padding: 1rem 0;
        margin: 1rem 0;
        border-bottom: var(--border);
        background: var(--main-color);
        color: var(--white);
        font-size: 1.8rem;
        width: 100%;
        max-width: 76rem;
        max-height: 20rem;
        min-height: 7rem;
        min-width: 76rem;
    }
</style>