<template>
    <div class="users-posts">
        <ul class="posts-container" v-if="storedPosts.length > 0">
            <PostItem v-for="post in storedPosts" :key="post.id" :post="post" @viewPost="viewPost" @deletedPost="deletedPost"/>
        </ul>
        <div v-if="storedPosts.length === 0">
            <p>No posts available.</p>
        </div>
    </div>
</template>

<script>
import PostItem from './PostItem.vue';

export default {
    props: {
        storedPosts: Object,
    },
    components: {
        PostItem,
    },
    methods: {
        viewPost(emittedPost) {
            this.$emit('viewPost', emittedPost);
            
        },
        closePost() {
            this.$emit('clickedPost', null);
        },
        deletedPost(postId) {
            this.$emit('deletedPost', postId);
        },
    },
}
</script>

<style>
    .users-posts {
        .posts-container {
            display: grid;
            grid-template-columns: repeat(1, 1fr);
            width: 100%;
            gap: 40px;
            padding: 30px 20px 80px 20px;
            @media screen and (min-width: 40em) {
                grid-template-columns: repeat(2, 1fr);
                gap: 20px 10px;
            }
            @media screen and (min-width: 64em) {
                grid-template-columns: repeat(3, 1fr);
            }
        }
    }
</style>