<?php 

// Pagination

// Pagination Options

class Pagination {

  private $url;

  private $totalRecords;
  private $page;
  private $recordsPerPage;
  private $index;

  private $pagePlusOne;
  private $pageMinusOne;
  private $pagePlusTwo;
  private $pageMinusTwo;
  private $totalPages;
  private $disablePrevious;
  private $disableNext;

  // Bootstrap settings
  private $paginationSize;

  public function __construct($recordCount, $settings = []) {

    // What if the user forgets to add the total record count as the first argument?
    if(is_array($recordCount)) {
      die('Error: record count is required');
    }

    $this->setURL();

    $this->totalRecords = $recordCount;
    $this->page = isset($settings['page']) ? $settings['page'] : 1;
    $this->recordsPerPage = isset($settings['recordsPerPage']) ? $settings['recordsPerPage'] : 10;
    $this->totalPages = ceil($this->totalRecords / $this->recordsPerPage);
    if($this->page > $this->totalPages) {
      $this->page = 1;
      $this->recordsPerPage = isset($settings['recordsPerPage']) ? $settings['recordsPerPage'] : 10;
      $this->totalPages = ceil($this->totalRecords / $this->recordsPerPage);
    }
    $this->pagePlusOne = $this->page + 1;
    $this->pageMinusOne = $this->page - 1;
    $this->pagePlusTwo = $this->page + 2;
    $this->pageMinusTwo = $this->page - 2;
    $this->index = ($this->page - 1) * $this->recordsPerPage;
    $this->disablePrevious = $this->page <= 1 ? 'disabled' : '';
    $this->disableNext = $this->page >= $this->totalPages ? 'disabled' : '';

  }

  public function setPage($page) {
    $this->page = $page;
    if($this->page > $this->totalPages) {
      $this->page = 1;
      $this->totalPages = ceil($this->totalRecords / $this->recordsPerPage);
    }
    $this->pagePlusOne = $this->page + 1;
    $this->pageMinusOne = $this->page - 1;
    $this->pagePlusTwo = $this->page + 2;
    $this->pageMinusTwo = $this->page - 2;
    $this->index = ($this->page - 1) * $this->recordsPerPage;
    $this->disablePrevious = $this->page <= 1 ? 'disabled' : '';
    $this->disableNext = $this->page >= $this->totalPages ? 'disabled' : '';
  }

  private function setURL() {

    if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
      $link = 'https';
    } else {
      $link = 'http';
    }
    $link .= "://";
    $link .= $_SERVER['HTTP_HOST'] . parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
    $this->url = $link;

  }

  public function getIndex() {

    return $this->index;

  }

  public function getRecordsPerPage() {

    return $this->recordsPerPage;

  }

  public function setRecordsPerPage($num) {

    $this->recordsPerPage = $num;
    $this->index = ($this->page - 1) * $this->recordsPerPage;
    $this->totalPages = ceil($this->totalRecords / $this->recordsPerPage);
    $this->disableNext = $this->page >= $this->totalPages ? 'disabled' : '';

  }

  public function setPaginationSize($size) {

    if($size == 'large') {
      $this->paginationSize = 'pagination-lg';
    } elseif($size == 'small') {
      $this->paginationSize = 'pagination-sm';
    } else {
      $this->paginationSize = '';
    }

  }

  public function pagination() {

    if ($this->totalPages > 0 && $this->page <= $this->totalPages) {
      echo '<nav aria-label="Page navigation">';
      echo '<ul class="pagination ' . $this->paginationSize . '">';

      echo '<li class="prev page-item ' . $this->disablePrevious . '"><a class="page-link" href="' . $this->url . '?page=' . $this->pageMinusOne . '">Prev</a></li>';
      
      if ($this->page > 3) {
        echo '<li class="start page-item"><a class="page-link" href="' . $this->url . '?page=1">1</a></li>';
        echo '<li class="dots page-item"><a class="page-link" href="">...</a></li>';
      }
      
      if($this->page - 2 > 0) {
        echo '<li class="page page-item"><a class="page-link" href="' . $this->url . '?page=' . $this->pageMinusTwo . '">' . $this->pageMinusTwo . '</a></li>';
      }
      if($this->page - 1 > 0) {
        echo '<li class="page-item"><a class="page-link" href="' . $this->url . '?page=' . $this->pageMinusOne . '">' . $this->pageMinusOne . '</a></li>';
      }
      
      echo '<li class="page-item active"><a class="page-link" href="' . $this->url . '?page=' . $this->page  . '">' . $this->page . '</a></li>';
      
      if (($this->page + 1) < ($this->totalPages + 1)) {
        echo '<li class="page-item"><a class="page-link" href="' . $this->url . '?page=' . $this->pagePlusOne . '">' . $this->pagePlusOne . '</a></li>';
      }
      if ($this->page + 2 < $this->totalPages + 1) {
        echo '<li class="page-item"><a class="page-link" href="' . $this->url . '?page=' . $this->pagePlusTwo . '">' . $this->pagePlusTwo  . '</a></li>';
      }
      
      if ($this->page < $this->totalPages - 2) {
        echo '<li class="dots page-item"><a class="page-link" href="">...</a></li>';
        echo '<li class="end page-item"><a class="page-link" href="' . $this->url . '?page=' . $this->totalPages . '">' . $this->totalPages . '</a></li>';
      }
      
      echo '<li class="next page-item ' . $this->disableNext . '"><a class="page-link" href="' . $this->url . '?page=' . $this->pagePlusOne . '">Next</a></li>';
      echo '</ul>';
      echo '</nav>';
    }

  }

}

?>