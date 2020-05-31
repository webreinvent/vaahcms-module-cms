<?php
if(isset($custom_class)){
    echo "<div class='".$custom_class."'>"."\n";
}

if(isset($groups) && $groups->count() > 0)
    {

        foreach ($groups as $group){
            if($group->fields->count() > 0){
                foreach($group->fields as $field){
                    if(isset($field->content)){

                        if(isset($field->meta->is_hidden) && $field->meta->is_hidden == 1)
                        {
                            continue;
                        }

                        if(isset($field->meta->container_tag)){
                            echo "<".$field->meta->container_tag." class='".$field->meta->container_attr_class."'>";
                        }
                        echo $field->content;
                        if(isset($field->meta->container_tag)){
                            echo "</".$field->meta->container_tag.">"."\n";
                        }
                    }
                }
            }
        }
    }

if(isset($custom_class)){
    echo "</div>";
}
