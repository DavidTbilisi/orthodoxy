import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap';
import 'jquery';
require('../css/main.scss');
import './comps/navbar';
import './comps/verse';
import './comps/modal';
import {Ajax} from './Ajax';
global.$ = $;
global.sharedData = {
    name: 'david'
};


global.vueInst = new Vue({
    el: '#root',
    data: function () {
        return {
            sharedData,
            word: "",
            result: '',
            bookTitles: [],
            bibleBooks:['',"დაბადება", "გამოსვლა", "ლევიანნი", "რიცხვნი", "მეორე რჯული", "იესო ნავეს ძე", "მსაჯული", "რუთი", "1 მეფეთა", "2 მეფეთა", "3 მეფეთა", "4 მეფეთა", "1 ნეშტთა", "2 ნეშტთა", "ეზრა", "ნეემია", "ესთერი", "იობი", "ფსალმუნები", "იგავნი სოლომონისა", "ეკლესიასტე", "ქებათა-ქება სოლომონისა", "ესაია", "იერემია", "გოდება იერემიასი", "ეზეკიელი", "დანიელი", "ოსია", "იოველი", "ამოსი", "აბდია", "იონა", "მიქა", "ნაუმი", "აბაკუმი", "სოფონია", "ანგია", "ზაქარია", "მალაქია", "მათეს სახარება", "მარკოზის სახარება", "ლუკას სახარება", "იოანეს სახარება", "მოციქულთა საქმეები", "იაკობის წერილი", "1 პეტრეს წერილი", "2 პეტრეს წერილი", "1 იოანე", "2 იოანე", "3 იოანე", "იუდა", "რომაელთა მიმართ", "1 კორინთელთა მიმართ", "2 კორინთელთა მიმართ", "გალატელთა მიმართ", "ეფესელთა მიმართ", "ფილიპელთა მიმართ", "კოლასელთა მიმართ", "1 თესალონიკელთა მიმართ", "2 თესალონიკელთა მიმართ", "1 ტიმოთეს მიმართ", "2 ტიმოთეს მიმართ", "ტიტეს მიმართ", "ფილიმონის მიმართ", "ებრაელთა მიმართ", "გამოცხადება"],
            book: 1,
            chapter: 1,
            verse: '',
            verses: '',
            maxChapt: "",
            last: '',
            bookAplha:'დაბადება თავი ',
            auto: false
        }
    },
    methods: {
        showResult() {
            "use strict";
            this.result = "მიმდინარეობს ძიება...";
            let ajax = new Ajax({
                method: 'post',
                data: {word: this.word},
                url: 'queries.php',
            });
            ajax.ok.then((d) => {
                this.result = JSON.parse(d);
                this.$forceUpdate();
            });
            ajax.ok.catch((err) => {
                console.log(err)
            })

        },
        getBookTitles() {
            "use strict";
            let ajax = new Ajax({
                method: 'post',
                data: {books: 'true'},
                url: 'queries.php',
            });

            ajax.ok.then((d) => {
                let resp = JSON.parse(d);
                resp.forEach((el, index) => {
                    this.bookTitles[++index] = el.name;
                });
                this.$forceUpdate();
            });
            ajax.ok.catch((err) => {
                console.log(err)
            })
        },
        highlight(text, word) {
            "use strict";
            if (word != undefined && text != undefined) {
                return text.replace(word, `<b style="background: ">${word}</b>`)
            }
        },
        /**************************************************/
        saveJson:function () {
            return new Ajax({
               url:"queries.php",
                method:'post',
                data:{
                   json:'json',
                }
            }).ok.then(data=>{
                "use strict";
                console.log(JSON.parse(data));
            })
        },


        /*----------------------------------------------------------------*/
        getFromDb: function () {
            return new Ajax({
                url: "save.php",
                method: 'post',
                data: {
                    last: 'last'
                }
            }).ok.then((d) => {
                "use strict";
                let resp = JSON.parse(d);
                console.log('already saved:', resp);
               // this.book = this.chapter > this.maxChapt ? this.book++ : resp.book || 1;
               // this.chapter = this.chapter > this.maxChapt ? 1 : parseInt(resp.chapter) + 1;
               // return resp;
            }).catch(er2 => {
                console.log(er2)
            })
        },
        getBible: function () {
            return new Ajax({
                url: 'hb.php',
                method: 'post',
                data: {
                    book: this.book,
                    chapter: this.chapter,
                }
            }).ok.then((data) => {
                "use strict";
// prepare next
                this.maxChapt = $(data)[4].value; // 50
                let chapter = $(data)[0].innerText; // დაბადება თავი 1
                let book = chapter.trim().replace(/ თავი \d+/,''); // დაბადება
                this.bookAplha = book;
                this.book = this.bibleBooks.findIndex(function (el) {
                    if (el != undefined){
                        return el.search(book) > -1; // 1
                    }
                });
                if (this.book > 66) {
                    return 0;
                }
                this.chapter = chapter.trim().match(/\d+$/)[0]; // 1
                console.log(book, this.chapter); // 1
                let fullChapterLoaded = parseInt(this.chapter) > parseInt(this.maxChapt);
                if (fullChapterLoaded) {  // 1 >= 50
                    this.book++; // 2
                    this.chapter=1;
                    this.getBible();
                    return 0;
                }

// read this
                // verses to arr
                let verses = [];
                $(data).find('.georgian').each((i, d) => {
                    verses.push($(d).text());
                });

                this.verses = verses;
                this.$forceUpdate();
                console.log('ready for sand to DataBase');
                if (this.auto) {
                    this.toDb(1);
                }
            }).catch(er2 => {
                console.log(er2)
            })
        },
        toDb: function (num) {
            return new Ajax({
                url: "save.php",
                method: 'post',
                data: {
                    save: 'save',
                    book: this.book,
                    chapter: this.chapter,
                    verse: this.verses[num - 1],
                    verse_id: num,
                }
            }).ok.then(saved => {
                "use strict";
                if (num > this.verses.length -2 ) {
                //    debugger;
                }
                if(num >= this.verses.length){
                    this.chapter++;
                    this.getBible();
                } else {
                    // load more verses
                    this.toDb(num + 1);
                    // console.log('saved ID:', saved);
                    console.log('saved verse:', num+1);
                }



            }).catch(er2 => {
                console.log(er2)
            })
        },
        init: function () {
            this.getBible();
        }


    },

    computed: {
        length: function () {
            "use strict";
            return this.result.length;
        },
    },
    mounted() {
        "use strict";
        this.getBookTitles();
        this.getFromDb();
        this.init();
    }
});

