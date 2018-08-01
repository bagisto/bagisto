<template>
    <ul class="nav">
        <category-item
            v-for="(item, index) in items"
            :key="index"
            :url="url"
            :item="item">
        </category-item>
        <li>
            <img class="icon" src="vendor/webkul/shop/assets/images/offer-zone.svg" style="margin-right:5px;" />Offer Zone
        </li>
    </ul>
</template>

<script>
// define the item component
export default {
	props: {
		categories: {
            type: [Array, String, Object],
            required: false,
            default: () => ([])
        },
        url: String
    },

    created () {
        console.log(this.url)
    },

    computed: {
        items () {
            return JSON.parse(this.categories)
        }
    }
}
</script>
<style>
    ul.nav {
        display: block;
        text-align: center;
        font-size:16px;
    }
    .nav ul {
        margin: 0;
        padding:0;
        list-style: none;
    }
    .nav a {
        display:block;
        color: black;
        text-decoration: none;
        padding: 0.8em 1.8em;
        text-transform: uppercase;
        letter-spacing: 2px;
        position: relative;
    }
    .nav{
        vertical-align: top;
        display: inline-block;
        box-shadow:
            1px -1px -1px 1px #000,
            -1px 1px -1px 1px #fff,
            0 0 6px 3px #fff;
        border-radius:6px;
    }
    .nav li {
        position: relative;
    }
    .nav > li {
        float: left;
        margin-right: 1px;
    }
    .nav > li > a {
        margin-bottom: 1px;
    }
    .nav > li:last-child {
        border-radius: 0 0 4px 0;
        margin-right: 0;
    }
    .nav > li:last-child > a {
        border-radius: 0 4px 0 0;
    }
    .nav li li a {
        margin-top: 1px;
    }

    .nav li a:first-child:nth-last-child(2):before {
        content: "";
        position: absolute;
        height: 0;
        width: 0;
        border: 5px solid transparent;
        top: 50% ;
        right:5px;
    }

    /* submenu positioning*/

    .nav ul {
        position: absolute;
        white-space: nowrap;
        border: 1px solid #B1B1B1;
        background-color:white;
        z-index: 1;
        left: -99999em;
    }
    .nav > li:hover > ul {
        left: auto;
        min-width: 100%;
    }
    .nav > li li:hover > ul {
        left: 100%;
        margin-left: 1px;
        top: -1px;
    }

.nav > li:hover > a:first-child:nth-last-child(2):before {
        /* border: 5px solid transparent;
        border-bottom-color: orange; */
        margin-top:-5px
    }
    .nav li li > a:first-child:nth-last-child(2):before {
        /* border-left-color: #aaa; */
        margin-top: -5px
    }
    .nav li li:hover > a:first-child:nth-last-child(2):before {
        right: 10px;
    }
</style>
