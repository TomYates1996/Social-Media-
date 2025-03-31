<template>
    <div class="comments">
        <div class="comment" v-for="comment in commentArr" :key="comment.id">
            <div class="content">
                <Link 
                    :href="'/profile/' + comment.user.username"
                    method="get"
                    class="username"
                >{{ comment.user.username }}</Link>
                <p>{{ comment.comment }}</p>
            </div>
            <button @click="toggleLike(comment.id, comment)">
                <div class="comment-likes" v-if="comment.likes">
                    {{ comment.likes.length }} 
                    <font-awesome-icon class="heart" v-if="comment.likes.findIndex(like => like.user_id === this.$page.props.auth.user.id || like.id === -247)" :icon="['far', 'heart']" />
                    <font-awesome-icon class="heart" v-if="!comment.likes.findIndex(like => like.user_id === this.$page.props.auth.user.id || like.id === -247)" :icon="['fas', 'heart']" />
                </div>
                <div v-if="!comment.likes" class="comment-likes-new-comment">
                    {{ 0 }} 
                    <font-awesome-icon class="heart" :icon="['far', 'heart']" />
                </div>
            </button>
        </div>

        <input v-model="newComment" placeholder="Write a comment...">
        <button @click="addComment">Post Comment</button>
    </div>
</template>

<script>
import { Link } from '@inertiajs/vue3';
import axios from 'axios';

export default {
    components: {
        Link
    },
    props: {
        postId: Number,
        comments: Array
    },
    data() {
        return {
            commentArr: [],
            newComment: '',
        };
    },
    created() {
        if(this.comments !== undefined) {
            this.commentArr = this.comments;
            this.commentLikes = this.comments.likes;
        };
    },
    methods: {
        async addComment() {
            if (this.newComment) {
                try {
                    const response = await axios.post(`/posts/${this.postId}/comments`, {
                        comment: this.newComment
                    });
                    response.data.comment.user = this.$page.props.auth.user;
                    this.commentArr.push(response.data.comment);
                    this.newComment = '';
                } catch (error) {
                    console.error('Error adding comment:', error);
                }
            }
        },
        async toggleLike(commentId, comment) {
            try {
                const response = await axios.post(`/comments/${commentId}/toggle-like`);
                const message = response.data.message;
                if (comment.likes) {
                    const hasUser = comment.likes.findIndex(like => like.user_id === this.$page.props.auth.user.id || like.id === -247);
                    if (hasUser !== -1) {
                        comment.likes.splice(hasUser, 1);
                    } else {
                        comment.likes.push({ id: -247, comment_id: 9999, user_id: this.$page.props.auth.user.id });
                    }
                    
                } else {
                    comment.likes = [
                            {
                                'user_id' : this.$page.props.auth.user.id,
                                'comment_id' : 9999,
                                'id' : -247,
                            }
                        ]

                    
                }
                console.log(message); 
            } catch (error) {
                console.error('comment like', error);
            }
        },
        getUsernameFromId(userId) {
            const response = axios.get(`/comments/${commentId}/toggle-like`);
        },
    }
};
</script>

<style>
.comments {
    display: flex;
    flex-direction: column;
    gap: 10px;
    .comment {
        display: flex;
        justify-content: space-between;
        .content {
            display: flex;
            align-items: flex-start;
            justify-content: flex-start;
            gap: 10px;
            .username {
                color: var(--link-colour);
                background-color: var(--white);
                padding: 0px 4px;
            }
        }
    }
}
</style>