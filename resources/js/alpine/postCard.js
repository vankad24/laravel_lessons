export default function postCard({ post, isUserAuth, loginRoute }) {
    return {
        post,
        comments: post.comments,

        commentsOpen: true,
        newCommentBody: '',
        isUserAuth,
        loginRoute,

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
        }
    }
}
