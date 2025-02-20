<?php
/**
 * @author Anastasia Sidak <m0st1ce.nastya@gmail.com>
 *
 * @link https://steamcommunity.com/profiles/76561198038416053
 * @link https://github.com/M0st1ce
 *
 * @license GNU General Public License Version 3
 */
?>
<style>
    .container-ranks{
        width: 100%;
        height: 100%;
        margin-left: 15px;
    }
    .row-rank {
        height: 45px;
        margin-bottom: 5px;
    }

    .row-rank .rank img {
        max-height: 35px;
    }

    .row-rank .line .i {
        background: rgba(255, 117, 0, 0.9);
        background-size: 5rem 5rem;
        background-image: linear-gradient(45deg, rgba(255, 255, 255, .15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, .15) 50%, rgba(255, 255, 255, .15) 75%, transparent 75%, transparent);
        height: 28px;
        max-width: 100%;
        border-radius: 4px;
    }

    .row-rank .line .value {
        font-size: 19px;
        font-weight: 600;
        color: var(--default-text-color);
        margin-top: -28px;
        margin-left: 20px;
    }

    @media (max-width: 767.98px) {
        .row-rank .rank {
            width: 3%;
            float: left;
            margin-right: 70px;
        }

        .row-rank .line {
            font-size: 18px;
            display: inline-block;
            font-weight: 400;
            width: 69%;
            background-color: var(--hover);
            border-radius: 4px;
            line-height: 30px;
            height: 28px
        }
    }

    @media (min-width: 768px) and (max-width: 991.98px) {
        .row-rank .rank {
            width: 3%;
            float: left;
            margin-right: 58px;
        }

        .row-rank .line {
            font-size: 18px;
            display: inline-block;
            font-weight: 400;
            width: 84%;
            background-color: var(--hover);
            border-radius: 4px;
            line-height: 30px;
            height: 28px
        }
    }

    @media (min-width: 992px) and (max-width: 1199.98px) {
        .row-rank .rank {
            width: 3%;
            float: left;
            margin-right: 55px;
        }

        .row-rank .line {
            font-size: 18px;
            display: inline-block;
            font-weight: 400;
            width: 87%;
            background-color: var(--hover);
            border-radius: 4px;
            line-height: 30px;
            height: 28px
        }
    }

    @media (min-width: 1200px) and (max-width: 1499.98px) {
        .row-rank .rank {
            width: 3%;
            float: left;
            margin-right: 55px;
        }

        .row-rank .line {
            font-size: 18px;
            display: inline-block;
            font-weight: 400;
            width: 88%;
            background-color: var(--hover);
            border-radius: 4px;
            line-height: 30px;
            height: 28px
        }
    }

    @media (min-width: 1500px){
        .row-rank .rank {
            width: 3%;
            float: left;
            margin-right: 35px;
        }

        .row-rank .line {
            font-size: 18px;
            display: inline-block;
            font-weight: 400;
            width: 93%;
            background-color: var(--hover);
            border-radius: 4px;
            line-height: 30px;
            height: 28px
        }
    }
</style>