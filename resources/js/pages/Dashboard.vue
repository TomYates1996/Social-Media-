<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';
import axios from 'axios';

</script>

<template>
    <div class="page-wrap">
        <Head title="Home" />

        <h1></h1>
        <PostsContainer :storedPosts="localPosts" @clickedPost="closePost" @deletedPost="deletedPost" @viewPost="viewPost"/>
    </div>
    <FullScreenPost v-if="clickedPost !== null" :post="clickedPost" @closePost="closePost" @deletedPost="deletedPost"/>
    <CreatePost v-if="showCreate" @showCreate="showCreatePost"/>
    <OptionsBar :page="0" :user="$page.props.auth.user"/>
    
</template>

<script>
import PostItem from '@/components/custom/PostItem.vue';
import FullScreenPost from '@/components/custom/FullScreenPost.vue';
import PostsContainer from '@/components/custom/PostsContainer.vue';
import OptionsBar from '@/components/custom/OptionsBar.vue';
import CreatePost from '../components/custom/CreatePost.vue';

export default {
    components: {
        PostItem,
        FullScreenPost,
        PostsContainer,
        OptionsBar,
        CreatePost
    },
    props: {
        posts: Array, 
    },
    data () {
        return {
            showCreate : false,
            localPosts: [],
            clickedPost: null,
        }
    },
    created() {
        this.localPosts = this.posts;
    },
    methods : {
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
    }
}
</script>

<style>
.page-wrap {
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    align-items: center;
}
    
</style>