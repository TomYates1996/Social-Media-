<template>
    <div class="page-wrap">
        <PostsContainer :storedPosts="localPosts" @clickedPost="closePost" @deletedPost="deletedPost" @viewPost="viewPost"/>
    </div>
    <OptionsBar :page="1" :user="$page.props.auth.user"/>
    <!-- <CreatePost v-if="showCreate" @showCreate="showCreatePost"/> -->
    <FullScreenPost v-if="clickedPost !== null" :post="clickedPost" @closePost="closePost" @deletedPost="deletedPost"/>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3';
import PostItem from '@/components/custom/PostItem.vue';
import FullScreenPost from '@/components/custom/FullScreenPost.vue';
import OptionsBar from '../components/custom/OptionsBar.vue';
import CreatePost from '@/components/custom/CreatePost.vue';
import PostsContainer from '@/components/custom/PostsContainer.vue';

export default {
    components: {
        Link,
        PostItem,
        FullScreenPost,
        OptionsBar,
        CreatePost,
        PostsContainer,
    },
    props: {
        posts: Array,
    },
    data () {
        return {
            clickedPost: null,
            localPosts: [],
            showCreate : false,
        }
    },
    created() {
        this.localPosts = this.posts;
    },
    methods: {
        showCreatePost(){
            this.showCreate = !this.showCreate;
        },
        viewPost(emittedPost) {
            this.clickedPost = emittedPost;
        },
        closePost() {
            this.clickedPost = null;
        },
        deletedPost(postId) {
            this.localPosts = this.localPosts.filter(post => post.id !== postId);
            this.clickedPost = null;
        },
    },
}
</script>

<style scoped>
.page-wrap {
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    align-items: center;
    .posts-container {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        max-width: 1200px;
        gap: 20px;
        .post-item {
            img {
                aspect-ratio: 1/1;
                width: 400px;
                height: 400px;
                object-fit: cover;
            }
        }
    }
}
</style>