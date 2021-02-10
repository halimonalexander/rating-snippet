<?php
namespace HalimonAlexander\HtmlSnippets;

class Rating
{
    const MAX_RATING  = 10;
    
    protected $ratingValueCssClass = '';
    protected $votesValueCssClass  = '';
    protected $votesWrapper        = '(%s votes)';
    protected $wrapperCssClass     = '';

    protected $starCssClasses = [
        "full" => 'icon-star-on',
        "half" => 'icon-star-half',
        "none" => 'icon-star-off',
    ];
    protected $starFormat = '<i class="icon-16 %s"></i>';
    
    private $ratingValueFormat = '<span itemprop="ratingValue" class="%s">%.1f</span><span>/</span><span>%d</span>';
    private $votesValueFormat  = '<span itemprop="ratingCount" class="%s">%d</span>';
    private $wrapperFormat     = '<div itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating" class="%s">%s</div>';
    
    private $showStars;
    private $showVotes;
    private $starsCount;
    
    public function __construct(bool $showVotes, bool $showStars, $startCount = 5)
    {
        $this->showVotes  = $showVotes;
        $this->showStars  = $showStars;
        $this->starsCount = $startCount;
    }
    
    public function fetch(float $rating, int $votesCount = 0): string
    {
        $data  = '<meta itemprop="worstRating" content="1">';
        $data .= '<meta itemprop="bestRating" content="' . self::MAX_RATING . '">';
        $data .= sprintf($this->ratingValueFormat, $this->ratingValueCssClass, $rating, self::MAX_RATING);
        
        if ($this->showVotes) {
            $data .= sprintf(
                $this->votesWrapper,
                sprintf(
                    $this->votesValueFormat,
                    $this->votesValueCssClass,
                    $votesCount
                )
            );
        }
        
        if ($this->showStars){
            $stars = iterator_to_array($this->getStars($rating));
            
            $data .= join("", $stars);
        }
        
        return sprintf($this->wrapperFormat, $this->wrapperCssClass, $data);
    }
    
    private function getStars(float $rating)
    {
        $ratingPerStar = round(self::MAX_RATING / $this->starsCount);
        
        $halfStars = round($rating / round($ratingPerStar / 2, 1));
        $fullStars = floor($halfStars / 2);
        
        $showHalfStar = (bool) ($halfStars - $fullStars * 2);
        
        for($i = 1; $i <= $this->starsCount; $i++) {
            if ($i <= $fullStars)
                yield sprintf($this->starFormat, $this->starCssClasses["full"]);
            elseif ($i == ($fullStars + 1) && $showHalfStar)
                yield sprintf($this->starFormat, $this->starCssClasses["half"]);
            else
                yield sprintf($this->starFormat, $this->starCssClasses["none"]);
        }
    }
}
