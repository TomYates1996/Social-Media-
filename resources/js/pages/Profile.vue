<template>

    <div class="page-wrap">
        <ProfileBanner :following="following" :followingCount="followingCount" :isFollowing="isFollowing" :user="user" :count="followerCount" :followers="followers"/>
        <PostsContainer :storedPosts="storedPosts" @clickedPost="closePost" @deletedPost="deletedPost" @viewPost="viewPost"/>
        
    </div>
    
    <OptionsBar :page="2" :user="user" />
    <FullScreenPost v-if="clickedPost !== null" :post="clickedPost" @closePost="closePost" @deletedPost="deletedPost"/>
    
</template>
  
<script>
import { Head, Link } from '@inertiajs/vue3';
import PostItem from '@/components/custom/PostItem.vue';
import FullScreenPost from '@/components/custom/FullScreenPost.vue';
import PostsContainer from '@/components/custom/PostsContainer.vue';
import OptionsBar from '@/components/custom/OptionsBar.vue';
import ProfileBanner from '@/components/custom/ProfileBanner.vue';


  export default {
    props: {
      user: Object,
      posts: Object,
      isFollowing: Boolean,
      followers: Array,
      followerCount: Number,
      authUser: Object,
      following: Array,
      followingCount: Number,
    },
    data() {
        return {
            clickedPost: null,
            storedPosts: [],
            followersProcessed: [],
            processCount: 0,
            showCreate : false,
        }
    },
    components: {
        Link,
        PostItem,
        FullScreenPost,
        PostsContainer,
        OptionsBar,
        ProfileBanner,
    },
    created() {
        this.storedPosts = this.posts;
        this.followersProcessed = this.followers;
        this.processCount = this.followerCount;
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
            this.storedPosts = this.storedPosts.filter(post => post.id !== postId);
            this.clickedPost = null;
        },
    }
  };
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
    }
}
</style>