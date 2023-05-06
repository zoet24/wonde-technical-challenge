interface ClassStudentsProps {
  students: {
    id: string;
    forename: string;
    surname: string;
  }[];
}

function ClassStudents({ students }: ClassStudentsProps) {
  return (
    <div className="p-2">
      <ul className="space-y-2 flex flex-wrap">
        {students.map((student) => (
          <li key={student.id} className="flex items-center w-full sm:w-1/2">
            <div className="mr-2 h-10 w-10 rounded-full border-white border-2 bg-blue-light flex items-center justify-center flex-shrink-0">
              <span>
                {student.forename.charAt(0)}
                {student.surname.charAt(0)}
              </span>
            </div>
            {student.forename} {student.surname}
          </li>
        ))}
      </ul>
    </div>
  );
}

export default ClassStudents;
