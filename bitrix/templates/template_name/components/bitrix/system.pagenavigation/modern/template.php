<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();


// Настройки
$nCurrent = 3;
$nEdge = 1;


$prevUrl = $APPLICATION->GetCurPageParam('PAGEN_1=' . ($arResult['NavPageNomer'] - 1), array('PAGEN_1'));
$nextUrl = $APPLICATION->GetCurPageParam('PAGEN_1=' . ($arResult['NavPageNomer'] + 1), array('PAGEN_1'));
$center = round($arResult['NavPageCount'] / 2);
if ($arResult['NavPageNomer'] == 2) {
    $prevUrl = $APPLICATION->GetCurPage();
}
if ($arResult['NavPageNomer'] == 1) {
    $prevUrl = false;
}
if ($arResult['NavPageNomer'] == $arResult['NavPageCount']) {
    $nextUrl = false;
}

$resultArray = array();

for ($i = 1; $i <= $arResult['NavPageCount']; $i++) {


    $showItem = false;

    $nearCenter = ((($i >= $center) and ($i <= ($center + $nCurrent))) or ($i <= $center and $i >= $center - $nCurrent));
    $nearCurrent = ((($i >= $arResult['NavPageNomer'] - $nCurrent) and $i <= $arResult['NavPageNomer']) or (($i <= $arResult['NavPageNomer'] + $nCurrent) and $i >= $arResult['NavPageNomer']));
    $nearFirst = ($i <= (1 + $nEdge));
    $nearLast = ($i >= ($arResult['NavPageCount'] - $nEdge));

    $itemArray = array(
        'PAGE_NUM' => $i
    );

    if ($nearCurrent or $nearCenter or $nearFirst or $nearLast) {

        $resultArray[] = $itemArray;
    }


}


foreach ($resultArray as $k => $v) {
    $nexPage = $resultArray[$k + 1];
    $url = $APPLICATION->GetCurPageParam('PAGEN_1=' . $v['PAGE_NUM'], array('PAGEN_1'));
    if ($arResult['NavPageNomer'] == $v['PAGE_NUM']) {
        $text = '<strong class="page-nav__title page-nav__title--current">' . $v['PAGE_NUM'] . '</strong>';
    } else {
        $text = '<a class="page-nav__title" href="' . $url . '">' . $v['PAGE_NUM'] . '</a>';
    }
    $result .= '<li class="page-nav__item">' . $text . '</li>';
    $r = ($nexPage['PAGE_NUM'] - $v['PAGE_NUM']);
    if ($r > 1 and $r != 2) {
        $result .= '<li class="page-nav__item"><span class="page-nav__title page-nav__title--more">&hellip;</span></li>';
    }
    if ($r == 2) {
        $result .= '<li class="page-nav__item"><a class="page-nav__title" href="' . $url . '">' . ($v['PAGE_NUM'] + 1) . '</a></li>';
    }
}





?>


<? if ($resultArray) {
    ?>
    <div class="page-nav">
        <ul class="page-nav__list clearfix">

            <li class="page-nav__item page-nav__item--prev"><a class="page-nav__title" href="<?= $prevUrl ?>">Предыдущая страница</a></li>

            <?= $result ?>

            <li class="page-nav__item page-nav__item--next"><a class="page-nav__title"
                                                               href="<?= $nextUrl ?>">Следующая страница</a></li>
        </ul>
    </div>

<?
}?>

 