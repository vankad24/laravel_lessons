export default function postCard({ post, authUserId, loginRoute }) {
    return {
        post,
        comments: post.comments,
        isUserAuth: authUserId!=null,
        isOwner: post.user.id === authUserId,
        commentsOpen: true,
        newCommentBody: '',
        loginRoute,

        // === edit state ===
        editing: false,
        editTitle: '',
        editContent: '',


        // === comments ===
        addComment() {
            if (!this.newCommentBody.trim()) return;

            axios.post('/api/comments', {
                body: this.newCommentBody,
                commentable_id: this.post.id,
                commentable_type: 'post'
            }).then(response => {
                this.comments.unshift(response.data.data);
                this.newCommentBody = '';
            });
        },

        // === likes ===
        likePost() {
            if (!this.isUserAuth) {
                window.location.href = this.loginRoute;
                return;
            }

            axios.post(`/api/posts/${this.post.id}/like`)
                .then(response => {
                    this.post.likes = response.data.data.likes;
                    this.post.is_liked_by_user =
                        response.data.data.is_liked_by_user;
                });
        },

        // === edit ===
        startEdit() {
            this.editing = true;
            this.editTitle = this.post.title;
            this.editContent = this.post.content;
        },

        cancelEdit() {
            this.editing = false;
            this.editTitle = '';
            this.editContent = '';
        },

        applyEdit() {
            axios.patch(`/api/posts/${this.post.id}`, {
                title: this.editTitle,
                content: this.editContent,
            }).then(() => {
                this.post.title = this.editTitle;
                this.post.content = this.editContent;
                this.editing = false;
            }).catch(err => {
                console.error(err.response?.data || err);
            });
        },

        deletePost() {
            if (!confirm('Удалить пост?')) return;

            axios.delete(`/api/posts/${this.post.id}`)
                .then(() => {
                    this.$root.remove();
                });
        }
    }
}
