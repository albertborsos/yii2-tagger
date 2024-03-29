<?php

    namespace albertborsos\yii2tagger\models;

    use albertborsos\yii2lib\db\ActiveRecord;
    use albertborsos\yii2lib\helpers\Glyph;
    use albertborsos\yii2lib\helpers\S;
    use albertborsos\yii2lib\helpers\Widgets;
    use albertborsos\yii2lib\wrappers\Select2;
    use Yii;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;

    /**
     * This is the model class for table "ext_tagger_tags".
     *
     * @property string $id
     * @property integer $sort_order
     * @property string $label
     * @property integer $created_at
     * @property integer $created_user
     * @property integer $updated_at
     * @property integer $updated_user
     * @property string $status
     *
     * @property Assigns[] $extTaggerAssigns
     */
    class Tags extends ActiveRecord {
        const STATUS_ACTIVE   = 'a';
        const STATUS_INACTIVE = 'i';
        const STATUS_DELETED  = 'd';

        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'ext_tagger_tags';
        }

        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                [['label', 'status'], 'trim'],
                [['label', 'status'], 'default'],
                [['label', 'status'], 'required'],
                [['sort_order', 'created_at', 'created_user', 'updated_at', 'updated_user'], 'integer'],
                [['label'], 'string', 'max' => 160],
                [['status'], 'string', 'max' => 1]
            ];
        }

        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'id'           => 'ID',
                'sort_order'   => 'Sorrend',
                'label'        => 'Cimke',
                'created_at'   => 'Létrehozva',
                'created_user' => 'Létrehozta',
                'updated_at'   => 'Módosítva',
                'updated_user' => 'Módosította',
                'status'       => 'Státusz',
            ];
        }

        /**
         * @return \yii\db\ActiveQuery
         */
        public function getAssigns()
        {
            return $this->hasMany(Assigns::className(), ['tag_id' => 'id']);
        }

        public function beforeValidate()
        {
            if (parent::beforeValidate()) {
                return true;
            } else {
                return false;
            }
        }

        public function beforeSave($insert)
        {
            if (parent::beforeSave($insert)) {
                $this->setOwnerAndTime();

                return true;
            } else {
                return false;
            }
        }

        public function beforeDelete()
        {
            if (parent::beforeDelete()) {
                return true;
            } else {
                return false;
            }
        }

        public static function Widget($id = 'tagger', $values, $byUser = false)
        {
            if ($byUser){
                $sourceArray   = self::getActiveTagsForSourceCreatedByUser();
            }else{
                $sourceArray   = self::getActiveTagsForSource();
            }
            $htmlOptions = [];
            $pluginOptions = Widgets::select2TagPluginOptions($sourceArray);

            $widget = '<div class="form-group field-'.$id.'">';
            $widget .= '<label class="control-label" for="' . $id . '">Cimkék</label>';
            $widget .= Select2::baseWidget($id, $values, $sourceArray, $htmlOptions, $pluginOptions);
            $widget .= '</div>';

            return $widget;
        }

        public static function activeWidget(\yii\base\Model $model, $name = 'tagger', $values, $byUser = false)
        {
            $id   = Html::getInputId($model, $name);
            $name = Html::getInputName($model, $name);

            if ($byUser){
                $sourceArray = self::getActiveTagsForSourceCreatedByUser();
            }else{
                $sourceArray   = self::getActiveTagsForSource();
            }
            $htmlOptions   = ['id' => $id];
            $pluginOptions = Widgets::select2TagPluginOptions($sourceArray);

            $widget = '<div class="form-group field-'.$id.'">';
            $widget .= '<label class="control-label" for="' . $id . '">Cimkék</label>';
            $widget .= Select2::baseWidget($name, $values, $sourceArray, $htmlOptions, $pluginOptions);
            $widget .= '</div>';

            return $widget;
        }

        public static function getActiveTagsForSource($model = null){
            $sql = 'SELECT DISTINCT t.label FROM '.Tags::tableName().' t' .
                ' JOIN '.Assigns::tableName().' a' .
                ' ON t.id=a.tag_id' .
                ' WHERE a.status=:status_a';
            if (is_null($model)){
                $query = Yii::$app->db->createCommand($sql, [
                    ':status_a' => Assigns::STATUS_ACTIVE,
                ]);
            }else{
                $sql .= ' AND model_class=:model_class';
                $query = Yii::$app->db->createCommand($sql, [
                    ':status_a' => Assigns::STATUS_ACTIVE,
                    ':model_class' => get_class($model),
                ]);
            }
            $tags = $query->queryAll();
            $source = [];
            foreach($tags as $tag){
                $source[] = $tag['label'];
            }
            return $source;
        }

        public static function getActiveTagsForSourceCreatedByUser($model = null){
            $sql = 'SELECT DISTINCT t.label FROM '.Tags::tableName().' t' .
                ' JOIN '.Assigns::tableName().' a' .
                ' ON t.id=a.tag_id' .
                ' WHERE a.created_user=:created_user';
            if (is_null($model)){
                $query = Yii::$app->db->createCommand($sql, [
                    ':created_user' => Yii::$app->getUser()->getId(),
                ]);
            }else{
                $sql .= ' AND model_class=:model_class';
                $query = Yii::$app->db->createCommand($sql, [
                    ':created_user' => Yii::$app->getUser()->getId(),
                    ':model_class' => get_class($model),
                ]);
            }
            $tags = $query->queryAll();
            $source = [];
            foreach($tags as $tag){
                $source[] = $tag['label'];
            }
            return $source;
        }

        public static function saveTo(ActiveRecord $model, $tagsInString, $separator = ','){
            if (!is_null($model->getPrimaryKey())){
                if ($tagsInString !== '' && !is_null($tagsInString)){
                    // lekérdezem a korábbi tag-eket (a törölteket is)
                    $assignedTags = self::getAssignedTags($model, false);
                    // felbontom az új tag-eket egy tömbre
                    $tags = explode(',', $tagsInString);
                    // az elküldött cimkékhez legyűjtöm az id_kat
                    $tagsWithIDs = self::matchLabelWithID($tags);
                    // most már megvan az összes cimkéhez az id-nk
                    // összehasonlítom, hogy mit kell törölni és mit kell hozzáadni
                    foreach($assignedTags as $assignedTag){ /* @var Assigns $assignedTag */
                        $savedToTags = false;
                        foreach($tagsWithIDs as $tagID => $tagLabel){
                            if ($assignedTag->tag_id == $tagID){
                                $savedToTags = true;
                            }
                        }
                        if($savedToTags){
                            // ha szerepel, akkor le kell ellenőriznem a státuszt db-ben
                            if($assignedTag->status == 'd'){
                                $assignedTag->status = 'a';
                                $assignedTag->save();
                            }
                        }else{
                            //ha nem szerepel az új cimkék közt, akkor törölni kell db-ből
                            $assignedTag->status = 'd';
                            $assignedTag->save();
                        }
                    }
                    foreach($tagsWithIDs as $tagID => $tagLabel){
                        $isAssignedToPost = false;
                        foreach($assignedTags as $assignedTag){ /* @var Assigns $assignedTag */
                            if ($assignedTag->tag_id == $tagID){
                                $isAssignedToPost = true;
                            }
                        }
                        if(!$isAssignedToPost){
                            // ha nincs hozzárendelve akkor menteni kell
                            $assign = new Assigns();
                            $assign->tag_id = $tagID;
                            $assign->model_class = get_class($model);
                            $assign->model_id = $model->getPrimaryKey();
                            $assign->status = 'a';
                            if (!$assign->save()){
                                $assign->throwNewException('Cimke hozzárendelési hiba!');
                            }
                        }
                    }
                }else{
                    // minden modelhez tartozó aktív cimkét törölni kell
                    $assignedTags = self::getAssignedTags($model);
                    foreach($assignedTags as $assignedTag){ /* @var Assigns $assignedTag */
                        $assignedTag->status = 'd';
                        $assignedTag->save();
                    }
                }
                return true;
            }else{
                $model->throwNewException('Előbb menteni kell a modelt, hogy hozzá lehessen fűzni a cimkéket!');
            }
        }

        /**
         * @param ActiveRecord $model
         * @param bool $activeOnly
         * @param string $returnType - object, array, string, link, label, dropdownlist
         * @param string $labelType - danger, info, success, warning
         * @return array|string|static[]
         */
        public static function getAssignedTags(ActiveRecord $model, $activeOnly = true, $returnType = 'object', $labelType= 'info'){
            if ($activeOnly){
                $assigns = Assigns::findAll([
                    'model_class' => get_class($model),
                    'model_id' => $model->getPrimaryKey(),
                    'status' => 'a',
                ]);
            }else{
                $assigns = Assigns::findAll([
                    'model_class' => get_class($model),
                    'model_id' => $model->getPrimaryKey(),
                ]);
            }
            $values = [];
            foreach($assigns as $assign){
                $values[$assign->tag_id] = $assign->tag->label;
            }
            switch($returnType){
                case 'object':
                    return $assigns;
                case 'array':
                    return array_values($values);
                case 'select2': // for select2 4.0
                    $values = array_values($values);
                    return array_combine($values, $values);
                case 'dropdownlist':
                    return $values;
                case 'string':
                    return implode(',', $values);
                case 'link':
                    $tags = null;
                    if(count($values) > 0){
                        $tags = Glyph::icon(Glyph::ICON_TAGS);
                        foreach($values as $value){
                            $tags .= ' '.Html::tag('span', $value, ['class' => 'label label-'.$labelType]);
                        }
                    }
                    return $tags;
                case 'label':
                    $icon = Glyph::icon(Glyph::ICON_TAGS);
                    $tags = '';
                    foreach($values as $value){
                        $tags .= ' '.Html::tag('span', $icon.' '.$value, ['class' => 'label label-'.$labelType]);
                    }
                    return $tags;
            }
        }

        public static function matchLabelWithID($labels){
            $tagsWithIDs = [];
            foreach($labels as $label){
                $foundTag = Tags::findOne(['label' => $label]);
                if (!is_null($foundTag)){
                    //létezik ilyen cimke
                    $tagsWithIDs[$foundTag->id] = $label;
                }else{
                    //még nincs ilyen cimke -> mentjük
                    if(trim($label) !== ''){
                        $tag = new Tags();
                        $tag->label = $label;
                        $tag->status = 'a';
                        $tag->save();
                        // már van ID-nk
                        $tagsWithIDs[$tag->id] = $label;
                    }
                }
            }
            return $tagsWithIDs;
        }

        public static function getLabelByID($id){
            $tag = self::findOne(['id' => $id]);
            return S::get($tag, 'label');
        }
    }
