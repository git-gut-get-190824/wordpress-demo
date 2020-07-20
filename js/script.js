jQuery(function () {

    /* モバイルのバーティカル表示のとき日曜日にクラス付与 */
    let mo = new MutationObserver(function () {
        // 対象要素
        let elems = document.querySelectorAll('.fc-list-header-right');

        // 対象ワード
        let targetWord = /日曜日/;

        elems.forEach(function(elem) {
            //console.log(elem.textContent);
                if ( targetWord.test(elem.textContent) ) {
                    console.log(elem.parentElement.parentElement.parentElement);
                    elem.parentElement.parentElement.parentElement.style.backgroundColor = '#F9CED3';
                    elem.parentElement.parentElement.parentElement.classList.add('sunday');
                }
            });
        
    });

    mo.observe(
        document.getElementById('eo_fullcalendar_1'),
        {
            childList: true,
            subtree: true
        }
    );

    // 実行を遅らせる
    // 最初の1回のみ対応可能
    // 中身が動的に生成されるので月移動に対応できない
    /*setTimeout(function(){
        // 対象要素
        let elems = document.querySelectorAll('.fc-list-header-right');

        // 対象ワード
        let targetWord = /日曜日/;
        elems.forEach(function(elem) {
            //console.log(elem.textContent);
                if ( targetWord.test(elem.textContent) ) {
                    console.log(elem.parentElement.parentElement.parentElement);
                    elem.parentElement.parentElement.parentElement.style.background = 'red';
                    //elem.parentElement.parentElement.classList.add('no_touch');
                }
            });
    }, 3000)
    */

    /**/
        // やり方1
        // EOカレンダー読み込みまでの時間が一定ではないので、失敗する可能性がある
        // 月移動したら中身書き換わるのでng
        /*setTimeout(function(){
            jQuery('span.fc-title').each(function(){
                let text = jQuery(this).text();
                if( text == '閉館日' ){
                    jQuery(this).parent().parent('a').addClass('no_touch');
                }
            })
        }, 1000);
        newStyle.innerText += '.mi-error-failure {position: fixed; opacity: 0; visibility: hidden; left: 50%; bottom: 100%; transform: translateX(-50%); width: calc(100% - (30px*2)); max-width: 1200px; max-height: calc( 100% - (60px + 30px*2) ); overflow-y: auto; background: #fff; color: #333; padding: 1.5rem 2.5rem; z-index: 11; transition: all .2s; box-shadow: 0 0 15px rgba(0,0,0,.7); background-image: repeating-linear-gradient(90deg, hsla(196,0%,79%,0.06) 0px, hsla(196,0%,79%,0.06) 1px,transparent 1px, transparent 96px),repeating-linear-gradient(0deg, hsla(196,0%,79%,0.06) 0px, hsla(196,0%,79%,0.06) 1px,transparent 1px, transparent 96px),repeating-linear-gradient(0deg, hsla(196,0%,79%,0.09) 0px, hsla(196,0%,79%,0.09) 1px,transparent 1px, transparent 12px),repeating-linear-gradient(90deg, hsla(196,0%,79%,0.09) 0px, hsla(196,0%,79%,0.09) 1px,transparent 1px, transparent 12px),linear-gradient(90deg, rgb(255,255,255),rgb(255,255,255));}';
        */

        // やり方2
        // DOMツリーの変更を監視する
        /*let mo = new MutationObserver(function() {
            let elems = document.querySelectorAll('span.fc-title');
            if (elems.length) {
              elems.forEach(function(elem) {
                if( elem.textContent == '閉館日' ){
                    elem.parentElement.parentElement.classList.add('no_touch');
                }
              });
          
            //   mo.disconnect();
              }
        });
        mo.observe(
                document.getElementById('eo_fullcalendar_1'),
                  {
                      childList: true,
                      subtree: true
                  }
              );
              */


}) // end of document.ready
