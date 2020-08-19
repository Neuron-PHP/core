[![Build Status](https://travis-ci.org/clearidea/neuron.svg?branch=master)](https://travis-ci.org/clearidea/neuron)

# About Neuron PHP

## Patterns

### Criteria

### Observer

An ObserverableTrait and IObserver interface make up the Observer pattern implementation.

    class Observable
    {
        use ObservableTrait;
        
        public function updated()
        {
            $this->notifyObservers( 1, 2, 3 );
        }
    }
    
    class Observer implements IObserver
    {
        public $State = 0;
        
        // IObserver implementation method..
        
        public function observableUpdate( $Observable, ...$param )
        {
            // Will set $State to 1.
            
        	$this->State = $param[ 0 ];
        }
    }

    $Observer   = new Observer;
    $Observable = new Observable;
    
    $Observable->addObserver( $Observer );
    
    $Observable->updated();
    
    // Will notify all attached observers..
    
    // Later on, clean up..
    
    $Observable->removeObserver( $Observer );
    
### Registry

### Singleton

