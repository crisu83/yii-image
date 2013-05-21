<?php
/**
 * ImageBehavior class file.
 * @author Christoffer Niska <ChristofferNiska@gmail.com>
 * @copyright Copyright &copy; Christoffer Niska 2011-
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @since 1.1.0
 */
class ImageBehavior extends CBehavior
{
	/**
	 * @var string the application component id for the imager.
	 */
	public $componentID = 'image';
	/**
	 * @var string the name of the image id column.
	 */
	public $idColumn = 'imageId';

	protected $_imager;

	/**
	 * Saves the image for the owner of this behavior.
	 * @param string $name the image name.
	 * @param string $path the path for saving the image.
	 * @param CUploadedFile $file the uploaded file.
	 * @return Image the model.
	 */
	public function saveImage($file, $name = null, $path = null)
	{
		$image = $this->getImager()->save($file, $name, $path);
		$this->owner->{$this->idColumn} = $image->id;
		$this->owner->save(false);
		return $image;
	}

	/**
	 * Render the image for the owner of this behavior.
	 * @param string $version the version name.
	 * @param string $alt the alternative text display.
	 * @param array $htmlOptions additional HTML attributes.
	 * @return string the rendered image.
	 */
	public function renderImage($version, $alt = '', $htmlOptions = array())
	{
		$image = $this->getImager()->load($this->owner->{$this->idColumn});
		return $image !== null ? CHtml::image($image->getUrl($version), $alt, $htmlOptions) : '';
	}

	/**
	 * Returns the url to the image for the owner of this behavior.
	 * @return string the url.
	 */
	public function getImageUrl($version) {
		$image = $this->getImager()->load($this->owner->{$this->idColumn});
		return $image !== null ? $image->getUrl($version) : '#';
	}

	/**
	 * Deletes the image for the owner of this behavior.
	 * @return boolean whether the image was deleted.
	 */
	public function deleteImage()
	{
		return $this->getImager()->delete($this->owner{$this->idColumn});
	}

	/**
	 * Returns the imager component instance.
	 * @return Imager the component.
	 */
	protected function getImager()
	{
		if (isset($this->_imager))
			return $this->_imager;
		else
			return $this->_imager = Yii::app()->getComponent($this->componentID);
	}
}