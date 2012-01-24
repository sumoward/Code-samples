public class LinkedStack {
	public class Node {
		Object element;
		Node next;

		public Node(Object element) {
			this.element = element;
		}

		public String toString() {
			return element.toString();
		}
	}

	int size;
	Node top;

	public LinkedStack() {
		top = null;
		size = 0;
	}

	public void push(Object o){
		
		
		Node node = new Node(o);
		node.next = top;
		top = node;
		size++;
	}

	public Object pop() throws ListEmptyException {

		if (top == null)
			throw new ListEmptyException();

		// hold the element
		Object holder = top.element;
		// set the tyop to null
		top.element = null;
		// set the new top
		top = top.next;
		// decrement the
		size--;
		return holder;
	}

	public Object top() throws ListEmptyException{
		if (top == null)
			throw new ListEmptyException();
		return top.element;
	}

	public int size() {
		return size;
	}

	public boolean isEmpty() {

		return size == 0;
	}

	// state visualisation
	public String toString() {
		String output = "";
		Node node = top;
		while (node != null) {
			output = node.element.toString() + " " + output;
			node = node.next;
		}

		return "" + size + "\t" + output;
	}
}