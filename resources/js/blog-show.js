document.addEventListener('DOMContentLoaded', () => {
    initLikeButton();
    initCommentForm();
    initReplyToggles();
    initCommentDelete();
    initCommentEdit();
});

function initLikeButton() {
    const likeBtn = document.getElementById('bhLikeBtn');
    if (!likeBtn) return;

    likeBtn.addEventListener('click', () => {
        if (likeBtn.dataset.guest) {
            window.location.href = '/login';
            return;
        }

        const url = likeBtn.dataset.likeUrl;
        const countEl = document.getElementById('bhLikeCount');
        const icon = likeBtn.querySelector('i');

        bhFetch(url, { method: 'POST' })
            .then(res => res.json())
            .then(data => {
                countEl.textContent = data.likes_count;
                likeBtn.classList.toggle('liked', data.liked);
                icon.className = data.liked ? 'bi bi-heart-fill me-2' : 'bi bi-heart me-2';
                likeBtn.classList.add('pulse');
                setTimeout(() => likeBtn.classList.remove('pulse'), 400);
            })
            .catch(() => alert('Something went wrong. Please try again.'));
    });
}

function initCommentForm() {
    const form = document.getElementById('bhCommentForm');
    if (!form) return;

    form.addEventListener('submit', (e) => {
        e.preventDefault();
        const textarea = form.querySelector('textarea');
        const errorArea = form.querySelector('.invalid-feedback-area');
        const url = form.dataset.url;

        errorArea.textContent = '';

        bhFetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ comment: textarea.value }),
        })
            .then(async (res) => {
                if (!res.ok) {
                    const err = await res.json();
                    throw new Error(err.errors?.comment?.[0] || 'Failed to post comment.');
                }
                return res.json();
            })
            .then((data) => {
                const list = document.getElementById('bhCommentsList');
                const placeholder = list.querySelector('p.text-muted');
                if (placeholder) placeholder.remove();
                list.insertAdjacentHTML('beforeend', data.html);
                document.getElementById('bhCommentsCount').textContent = data.comments_count;
                textarea.value = '';
            })
            .catch((err) => {
                errorArea.textContent = err.message;
            });
    });
}

function initReplyToggles() {
    document.body.addEventListener('click', (e) => {
        if (e.target.matches('.bh-reply-toggle')) {
            e.preventDefault();
            const id = e.target.dataset.commentId;
            const form = document.querySelector(`.bh-reply-form[data-comment-id="${id}"]`);
            if (form) form.classList.toggle('d-none');
        }
    });

    document.body.addEventListener('submit', (e) => {
        if (!e.target.matches('.bh-reply-form')) return;
        e.preventDefault();

        const form = e.target;
        const input = form.querySelector('input[name="comment"]');
        const parentId = form.querySelector('input[name="parent_id"]').value;
        const url = form.dataset.url;

        bhFetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ comment: input.value, parent_id: parentId }),
        })
            .then(async (res) => {
                if (!res.ok) {
                    const err = await res.json();
                    throw new Error(err.errors?.comment?.[0] || 'Failed to post reply.');
                }
                return res.json();
            })
            .then((data) => {
                const repliesList = document.getElementById(`replies-${parentId}`);
                repliesList.insertAdjacentHTML('beforeend', data.html);
                document.getElementById('bhCommentsCount').textContent = data.comments_count;
                input.value = '';
                form.classList.add('d-none');
            })
            .catch((err) => alert(err.message));
    });
}

function initCommentDelete() {
    document.body.addEventListener('click', (e) => {
        if (!e.target.matches('.bh-comment-delete')) return;
        e.preventDefault();

        if (!confirm('Delete this comment?')) return;

        const id = e.target.dataset.commentId;
        const url = e.target.dataset.url;

        bhFetch(url, { method: 'DELETE' })
            .then(res => res.json())
            .then((data) => {
                document.getElementById(`comment-${id}`)?.remove();
                document.getElementById('bhCommentsCount').textContent = data.comments_count;
            })
            .catch(() => alert('Failed to delete comment.'));
    });
}

function initCommentEdit() {
    document.body.addEventListener('click', (e) => {
        if (!e.target.matches('.bh-comment-edit')) return;
        e.preventDefault();

        const btn = e.target;
        const id = btn.dataset.commentId;
        const commentDiv = document.getElementById(`comment-${id}`);
        if (!commentDiv) return;

        const textEl = commentDiv.querySelector('.comment-text');
        if (!textEl || commentDiv.querySelector('.bh-edit-form')) return;

        const originalText = textEl.textContent;
        textEl.classList.add('d-none');

        // Create edit form
        const form = document.createElement('form');
        form.className = 'bh-edit-form mt-2';
        form.innerHTML = `
            <div class="input-group">
                <input type="text" class="form-control bh-form-control" value="${originalText.replace(/"/g, '&quot;')}" required>
                <button type="submit" class="btn btn-gradient btn-sm">Save</button>
                <button type="button" class="btn btn-light border btn-sm bh-edit-cancel">Cancel</button>
            </div>
        `;

        textEl.parentNode.appendChild(form);

        const input = form.querySelector('input');
        input.focus();

        // Cancel editing
        form.querySelector('.bh-edit-cancel').addEventListener('click', () => {
            form.remove();
            textEl.classList.remove('d-none');
        });

        // Submit edit
        form.addEventListener('submit', (evt) => {
            evt.preventDefault();
            const updatedText = input.value.trim();
            if (!updatedText) return;

            const url = `/comments/${id}`;

            bhFetch(url, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ comment: updatedText }),
            })
                .then(async (res) => {
                    if (!res.ok) {
                        const err = await res.json();
                        throw new Error(err.errors?.comment?.[0] || 'Failed to update comment.');
                    }
                    return res.json();
                })
                .then((data) => {
                    textEl.textContent = data.comment;
                    form.remove();
                    textEl.classList.remove('d-none');
                })
                .catch((err) => alert(err.message));
        });
    });
}